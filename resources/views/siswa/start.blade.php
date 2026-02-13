<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ujian: {{ $exam->title }} - CBT SPMB</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FONT ARAB BARU -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
            font-size: 1.27rem; /* sedikit lebih besar global */
        }

        .exam-container {
            max-width: 950px;
            margin: auto;
        }

        .timer-box {
            font-size: 1.35rem; /* diperbesar */
            font-weight: 600;
        }

        .question-card {
            display: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .question-card.active {
            display: block;
        }

        .question-title {
            font-size: 1.5rem; /* diperbesar */
            font-weight: 600;
            line-height: 1.8;
        }

        .option-item {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 12px 18px;
            margin-bottom: 12px;
            transition: 0.2s ease;
            cursor: pointer;
            font-size: 1.3rem; /* diperbesar */
        }

        .option-item:hover {
            background: #f1f3f5;
        }

        .question-nav {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(40px,1fr));
            gap: 6px;
        }

        .answered {
            background-color: #198754 !important;
            color: white !important;
            border-color: #198754 !important;
        }

        /* FULL ARAB */
        .arabic-full {
            font-family: 'Noto Naskh Arabic', serif;
            direction: rtl;
            text-align: right;
            font-size: 1.8rem; /* diperbesar */
            line-height: 2.2;
        }

        /* MIXED */
        .arabic-mixed {
            font-family: 'Noto Naskh Arabic', Arial, sans-serif;
            direction: ltr;
            text-align: left;
            font-size: 1.55rem; /* diperbesar */
            line-height: 2;
        }

        textarea.form-control {
            font-size: 1.15rem; /* essay juga ikut besar */
        }

    </style>
</head>
<body>

<div class="container py-4 exam-container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Ujian: {{ $exam->title }}</h4>
        <div class="alert alert-warning mb-0 timer-box" id="timer"></div>
    </div>

    <button class="btn btn-outline-secondary mb-3" data-bs-toggle="collapse" data-bs-target="#questionNav">
        Navigasi Soal
    </button>

    <div class="collapse mb-3" id="questionNav">
        <div class="question-nav">
            @foreach($exam->questions as $i => $q)
                <button type="button"
                        class="btn btn-outline-primary btn-sm nav-btn"
                        data-index="{{ $i }}">
                    {{ $i+1 }}
                </button>
            @endforeach
        </div>
    </div>

    <form method="POST" id="examForm">
        @csrf

        @foreach($exam->questions as $index => $q)
            <div class="card question-card {{ $loop->first ? 'active' : '' }}"
                 data-index="{{ $index }}">
                <div class="card-body">

                    <div class="question-title mb-3 question-text">
                        {{ $index+1 }}. {{ $q->question }}
                    </div>

                    @if($q->image)
                        <img src="{{ asset('storage/' . $q->image) }}"
                             class="img-fluid mb-3"
                             style="max-height:300px">
                    @endif

                    @if($q->type == 'pg')
                        @foreach(['A','B','C','D','E'] as $opt)
                            @php $field = 'option_'.strtolower($opt); @endphp
                            @if($q->$field)
                                <label class="option-item option-text w-100">
                                    <input type="radio"
                                           name="answers[{{ $q->id }}]"
                                           value="{{ $opt }}">
                                    <strong>{{ $opt }}.</strong> {{ $q->$field }}
                                </label>
                            @endif
                        @endforeach
                    @endif

                    @if($q->type == 'essay')
                        <textarea name="answers[{{ $q->id }}]"
                                  class="form-control"
                                  rows="4"
                                  placeholder="Tulis jawaban..."></textarea>
                    @endif

                    <div class="d-flex justify-content-between mt-4">
                        <button type="button"
                                class="btn btn-secondary prev-btn"
                                data-index="{{ $index }}"
                                {{ $index==0?'disabled':'' }}>
                            Sebelumnya
                        </button>

                        @if($index < count($exam->questions)-1)
                            <button type="button"
                                    class="btn btn-primary next-btn"
                                    data-index="{{ $index }}">
                                Selanjutnya
                            </button>
                        @else
                            <button type="button"
                                    class="btn btn-success"
                                    id="submitExam">
                                Submit Ujian
                            </button>
                        @endif
                    </div>

                </div>
            </div>
        @endforeach
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const examForm = document.getElementById('examForm');
const questions = document.querySelectorAll('.question-card');
const navButtons = document.querySelectorAll('.nav-btn');
let duration = {{ $exam->duration }} * 60;

/* ================= TIMER ================= */
function formatTime(sec){
    const m = String(Math.floor(sec/60)).padStart(2,'0');
    const s = String(sec%60).padStart(2,'0');
    return `${m}:${s}`;
}

const timerDiv = document.getElementById('timer');
const timer = setInterval(()=>{
    timerDiv.innerHTML = "Sisa Waktu: " + formatTime(duration);
    duration--;
    if(duration < 0){
        clearInterval(timer);
        examForm.submit();
    }
},1000);

/* ================= NAVIGATION ================= */
function showQuestion(index){
    questions.forEach(q=>q.classList.remove('active'));
    document.querySelector(`.question-card[data-index="${index}"]`)
        .classList.add('active');
}

document.querySelectorAll('.next-btn').forEach(btn=>{
    btn.addEventListener('click',()=>{
        showQuestion(parseInt(btn.dataset.index)+1);
    });
});

document.querySelectorAll('.prev-btn').forEach(btn=>{
    btn.addEventListener('click',()=>{
        showQuestion(parseInt(btn.dataset.index)-1);
    });
});

navButtons.forEach(btn=>{
    btn.addEventListener('click',()=>{
        showQuestion(btn.dataset.index);
    });
});

/* ================= DETEKSI ARAB ================= */
function getArabicPercentage(text){
    const arabic = text.match(/[\u0600-\u06FF]/g);
    if(!arabic) return 0;
    return (arabic.length / text.length) * 100;
}

function applyArabicStyling(){
    document.querySelectorAll('.question-text, .option-text')
        .forEach(el=>{
            const text = el.innerText.trim();
            const percent = getArabicPercentage(text);

            el.classList.remove('arabic-full','arabic-mixed');

            if(percent > 60){
                el.classList.add('arabic-full');
            }else if(percent > 10){
                el.classList.add('arabic-mixed');
            }
        });
}

document.addEventListener('DOMContentLoaded', applyArabicStyling);

/* ================= AUTOSAVE ================= */
function saveProgress(){
    const formData = new FormData(examForm);
    const answers = {};
    for (let [key,value] of formData.entries()){
        if(key.startsWith('answers[')){
            const qid = key.match(/\[(\d+)\]/)[1];
            answers[qid] = value;
        }
    }

    fetch('/siswa/ujian/{{ $exam->id }}/save-progress',{
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({answers})
    });
}

setInterval(saveProgress,30000);

window.onbeforeunload = function(){
    return "Ujian sedang berlangsung.";
};

/* ================= SUBMIT EXAM ================= */
document.getElementById('submitExam').addEventListener('click', function() {
    if(confirm('Apakah Anda yakin ingin Submit Ujian? Jawaban yang belum tersimpan akan hilang.')){
        examForm.submit();
    }
});
</script>

</body>
</html>
