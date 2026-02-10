<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ujian: {{ $exam->title }} - CBT SPMB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .question { display: none; }
        .question.active { display: block; }
        .timer { font-size: 1.2em; font-weight: bold; color: red; }
        .options { margin: 10px 0; }
        .option { margin: 5px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Ujian: {{ $exam->title }}</h2>

        <div id="timer" class="timer alert alert-warning"></div>

        <div class="question-nav mb-3 d-flex flex-wrap">
            @for($i = 0; $i < count($exam->questions); $i++)
                <button type="button" class="btn btn-outline-primary me-1" onclick="gotoQuestion({{ $i }})">{{ $i+1 }}</button>
            @endfor
        </div>

        <form method="POST" id="examForm">
            @csrf
            @foreach($exam->questions as $index => $q)
                <div class="question card mb-3 {{ $loop->first ? 'active' : '' }}" data-index="{{ $loop->index }}">
                    <div class="card-body">
                        <h5>{{ $index+1 }}. {{ $q->question }}</h5>
                        @if ($q->image)
                            <img src="{{ asset('storage/' . $q->image) }}" alt="Gambar Soal" class="img-thumbnail mb-2" style="max-width: 400px;">
                        @endif

                        @if($q->type=='pg')
                            <div class="options">
                                <div class="option">
                                    <input type="radio" name="answers[{{ $q->id }}]" value="A" id="q{{ $q->id }}a" {{ isset($existingResult->progress[$q->id]) && $existingResult->progress[$q->id] == 'A' ? 'checked' : '' }}>
                                    <label for="q{{ $q->id }}a" @if($q->option_a_image) style="display: flex; align-items: flex-start;" @endif>
                                        @if($q->option_a_image)
                                            <span>A. {{ $q->option_a }}</span>
                                            <img src="{{ asset('storage/' . $q->option_a_image) }}" alt="Gambar Opsi A" class="img-fluid ml-2" style="max-width: 150px;">
                                        @else
                                            A. {{ $q->option_a }}
                                        @endif
                                    </label>
                                </div>
                                <div class="option">
                                    <input type="radio" name="answers[{{ $q->id }}]" value="B" id="q{{ $q->id }}b" {{ isset($existingResult->progress[$q->id]) && $existingResult->progress[$q->id] == 'B' ? 'checked' : '' }}>
                                    <label for="q{{ $q->id }}b" @if($q->option_b_image) style="display: flex; align-items: flex-start;" @endif>
                                        @if($q->option_b_image)
                                            <span>B. {{ $q->option_b }}</span>
                                            <img src="{{ asset('storage/' . $q->option_b_image) }}" alt="Gambar Opsi B" class="img-fluid ml-2" style="max-width: 150px;">
                                        @else
                                            B. {{ $q->option_b }}
                                        @endif
                                    </label>
                                </div>
                                <div class="option">
                                    <input type="radio" name="answers[{{ $q->id }}]" value="C" id="q{{ $q->id }}c" {{ isset($existingResult->progress[$q->id]) && $existingResult->progress[$q->id] == 'C' ? 'checked' : '' }}>
                                    <label for="q{{ $q->id }}c" @if($q->option_c_image) style="display: flex; align-items: flex-start;" @endif>
                                        @if($q->option_c_image)
                                            <span>C. {{ $q->option_c }}</span>
                                            <img src="{{ asset('storage/' . $q->option_c_image) }}" alt="Gambar Opsi C" class="img-fluid ml-2" style="max-width: 150px;">
                                        @else
                                            C. {{ $q->option_c }}
                                        @endif
                                    </label>
                                </div>
                                <div class="option">
                                    <input type="radio" name="answers[{{ $q->id }}]" value="D" id="q{{ $q->id }}d" {{ isset($existingResult->progress[$q->id]) && $existingResult->progress[$q->id] == 'D' ? 'checked' : '' }}>
                                    <label for="q{{ $q->id }}d" @if($q->option_d_image) style="display: flex; align-items: flex-start;" @endif>
                                        @if($q->option_d_image)
                                            <span>D. {{ $q->option_d }}</span>
                                            <img src="{{ asset('storage/' . $q->option_d_image) }}" alt="Gambar Opsi D" class="img-fluid ml-2" style="max-width: 150px;">
                                        @else
                                            D. {{ $q->option_d }}
                                        @endif
                                    </label>
                                </div>
                                @if($q->option_e)
                                    <div class="option">
                                        <input type="radio" name="answers[{{ $q->id }}]" value="E" id="q{{ $q->id }}e" {{ isset($existingResult->progress[$q->id]) && $existingResult->progress[$q->id] == 'E' ? 'checked' : '' }}>
                                        <label for="q{{ $q->id }}e" @if($q->option_e_image) style="display: flex; align-items: flex-start;" @endif>
                                            @if($q->option_e_image)
                                                <span>E. {{ $q->option_e }}</span>
                                                <img src="{{ asset('storage/' . $q->option_e_image) }}" alt="Gambar Opsi E" class="img-fluid ml-2" style="max-width: 150px;">
                                            @else
                                                E. {{ $q->option_e }}
                                            @endif
                                        </label>
                                    </div>
                                @endif
                            </div>
                        @endif
                        @if ($q->essay)
                            <textarea name="answers[{{ $q->id }}]" class="form-control" rows="4" placeholder="Jawaban Anda">{{ isset($existingResult->progress[$q->id]) ? $existingResult->progress[$q->id] : '' }}</textarea>
                        @endif

                        <div class="mt-3">
                            @if($index>0)
                                <button type="button" class="btn btn-secondary" onclick="prev({{ $index }})">Sebelumnya</button>
                            @endif
                            @if($index < count($exam->questions)-1)
                                <button type="button" class="btn btn-primary" onclick="next({{ $index }})">Selanjutnya</button>
                            @else
                                <button type="button" class="btn btn-success" onclick="confirmSubmit()">Submit Ujian</button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </form>
    </div>

    <script>
        let duration = {{ $exam->duration }} * 60;
        let timerDiv = document.getElementById('timer');
        let examForm = document.getElementById('examForm');

        let timer = setInterval(()=>{
            let minutes = Math.floor(duration/60);
            let seconds = duration%60;
            timerDiv.innerHTML = '<strong>Sisa Waktu: ' + minutes + ' menit ' + seconds + ' detik</strong>';
            duration--;
            if(duration<0){
                clearInterval(timer);
                alert('Waktu habis, ujian akan disubmit otomatis!');
                examForm.submit();
            }
        },1000);

        // navigasi soal
        function next(i){
            document.querySelector('.question[data-index="'+i+'"]').classList.remove('active');
            document.querySelector('.question[data-index="'+(i+1)+'"]').classList.add('active');
        }
        function prev(i){
            document.querySelector('.question[data-index="'+i+'"]').classList.remove('active');
            document.querySelector('.question[data-index="'+(i-1)+'"]').classList.add('active');
        }
        function gotoQuestion(i){
            document.querySelectorAll('.question').forEach(q => q.classList.remove('active'));
            document.querySelector('.question[data-index="'+i+'"]').classList.add('active');
        }

        // konfirmasi submit
        function confirmSubmit(){
            if(confirm('Apakah Anda yakin ingin mengirimkan jawaban? Pastikan semua jawaban sudah benar.')){
                examForm.submit();
            }
        }

        // save progress
        function saveProgress(){
            let formData = new FormData(examForm);
            let answers = {};
            for (let [key, value] of formData.entries()) {
                if (key.startsWith('answers[')) {
                    let qid = key.match(/answers\[(\d+)\]/)[1];
                    answers[qid] = value;
                }
            }
            fetch('/siswa/ujian/{{ $exam->id }}/save-progress', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ answers: answers })
            });
        }

        // save every 30 seconds
        setInterval(saveProgress, 30000);

        // save on input change
        document.querySelectorAll('input[type="radio"], textarea').forEach(el => {
            el.addEventListener('change', saveProgress);
        });

        // anti refresh / close tab
        window.onbeforeunload = function(){
            return "Ujian sedang berlangsung! Pastikan submit sebelum keluar.";
        }

        // load saved progress
        const savedProgress = @json($existingResult->progress ?? []);
        for (const [qid, ans] of Object.entries(savedProgress)) {
            const input = document.querySelector(`input[name="answers[${qid}]"][value="${ans}"]`);
            if (input) input.checked = true;
        }

        // save progress every 30 seconds and on change
        function saveProgress() {
            const formData = new FormData(examForm);
            const answers = {};
            for (let [key, value] of formData.entries()) {
                if (key.startsWith('answers[')) {
                    const qid = key.match(/answers\[(\d+)\]/)[1];
                    answers[qid] = value;
                }
            }
            fetch('/siswa/ujian/{{ $exam->id }}/save-progress', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ answers })
            });
        }

        setInterval(saveProgress, 30000);
        document.querySelectorAll('input[type="radio"]').forEach(input => {
            input.addEventListener('change', saveProgress);
        });
    </script>
</body>
</html>
