<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ujian: {{ $exam->title }} - CBT SMKS AL-FALAH NAGREG</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #eef2f7, #f8fafc);
            font-size: 1.1rem;
            font-family: 'Inter', sans-serif;
        }

        .exam-container {
            max-width: 1100px;
            margin: auto;
        }

        .topbar {
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(12px);
            padding: 15px 20px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .timer-box {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .question-card {
            display: none;
            border: none;
            border-radius: 18px;
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(12px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.06);
        }

        .question-card.active {
            display: block;
        }

        .question-title {
            font-size: 1.3rem;
            font-weight: 600;
            line-height: 1.7;
        }

        .option-item {
            border: 1px solid #dee2e6;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 12px;
            transition: 0.2s;
            cursor: pointer;
            font-size: 1.1rem;
            display: block;
        }

        .option-item:hover {
            background: #f1f3f5;
        }

        .option-item input {
            margin-right: 10px;
        }

        .question-nav {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(45px,1fr));
            gap: 8px;
        }

        .question-nav button {
            border-radius: 10px;
        }

        .answered {
            background-color: #198754 !important;
            color: white !important;
        }

        textarea.form-control {
            font-size: 1rem;
            border-radius: 12px;
        }

        img {
            border-radius: 10px;
            max-width: 100%;
            height: auto;
        }

        .arabic-full {
            font-family: 'Noto Naskh Arabic', serif;
            direction: rtl;
            text-align: right;
            font-size: 1.6rem;
            line-height: 2;
        }

        .arabic-mixed {
            font-family: 'Noto Naskh Arabic', Arial, sans-serif;
            direction: ltr;
            text-align: left;
            font-size: 1.3rem;
            line-height: 1.9;
        }

        .nav-control {
            position: sticky;
            bottom: 0;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
            padding: 10px;
            border-top: 1px solid #eee;
            border-radius: 0 0 18px 18px;
        }

    </style>
</head>

<body>

<div class="container py-4 exam-container">

    <!-- TOPBAR -->
    <div class="topbar d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Ujian: {{ $exam->title }}</h5>
        <div class="alert alert-warning mb-0 timer-box" id="timer"></div>
    </div>

    <button class="btn btn-outline-secondary mb-3" data-bs-toggle="collapse" data-bs-target="#questionNav">
        Navigasi Soal
    </button>

    <div class="collapse mb-3" id="questionNav">
        <div class="question-nav">
            @foreach($exam->questions as $i => $q)
                <button type="button" class="btn btn-outline-primary btn-sm nav-btn" data-index="{{ $i }}">
                    {{ $i+1 }}
                </button>
            @endforeach
        </div>
    </div>

    <form method="POST" id="examForm">
        @csrf

        @foreach($exam->questions as $index => $q)
            <div class="card question-card {{ $loop->first ? 'active' : '' }}" data-index="{{ $index }}">
                <div class="card-body">

                    <div class="question-title mb-3 question-text">
                        {{ $index+1 }}. {{ $q->question }}
                    </div>

                    @if($q->image)
                        <img src="{{ asset('storage/' . $q->image) }}" class="mb-3">
                    @endif

                    @if($q->type == 'pg')
                        @foreach(['A','B','C','D','E'] as $opt)
                            @php 
                                $field = 'option_'.strtolower($opt); 
                                $img_field = $field.'_image'; 
                            @endphp

                            @if($q->$field || $q->$img_field)
                                <label class="option-item option-text">
                                    <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}">
                                    <strong>{{ $opt }}.</strong> {{ $q->$field }}

                                    @if($q->$img_field)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $q->$img_field) }}">
                                        </div>
                                    @endif
                                </label>
                            @endif
                        @endforeach
                    @endif

                    @if($q->type == 'essay')
                        <textarea name="answers[{{ $q->id }}]" class="form-control mt-2" rows="4"
                            placeholder="Tulis jawaban..."></textarea>
                    @endif

                </div>

                <!-- NAV STICKY -->
                <div class="nav-control d-flex justify-content-between">
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
        @endforeach
    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const examForm = document.getElementById('examForm');
const questions = document.querySelectorAll('.question-card');
const navButtons = document.querySelectorAll('.nav-btn');
let duration = {{ $exam->duration }} * 60;

/* TIMER */
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

/* NAV */
function showQuestion(index){
    questions.forEach(q=>q.classList.remove('active'));
    document.querySelector(`.question-card[data-index="${index}"]`)
        .classList.add('active');
}

document.querySelectorAll('.next-btn').forEach(btn=>{
    btn.onclick = ()=> showQuestion(+btn.dataset.index + 1);
});

document.querySelectorAll('.prev-btn').forEach(btn=>{
    btn.onclick = ()=> showQuestion(+btn.dataset.index - 1);
});

navButtons.forEach(btn=>{
    btn.onclick = ()=> showQuestion(btn.dataset.index);
});

/* ARAB */
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

/* AUTOSAVE */
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

/* SUBMIT */
document.getElementById('submitExam').onclick = function(){
    if(confirm('Yakin submit ujian?')){
        sessionStorage.clear();
        examForm.submit();
    }
};
</script>

</body>
</html>