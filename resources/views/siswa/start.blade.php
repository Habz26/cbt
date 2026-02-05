<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian: {{ $exam->title }} - CBT UAS</title>
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
                <div class="question card mb-3 {{ $index == 0 ? 'active' : '' }}" data-index="{{ $index }}">
                    <div class="card-body">
                        <h5>{{ $index+1 }}. {{ $q->question }}</h5>

                        @if($q->type=='pg')
                            <div class="options">
                                <div class="option">
                                    <input type="radio" name="answers[{{ $q->id }}]" value="A" id="q{{ $q->id }}a">
                                    <label for="q{{ $q->id }}a">A. {{ $q->option_a }}</label>
                                </div>
                                <div class="option">
                                    <input type="radio" name="answers[{{ $q->id }}]" value="B" id="q{{ $q->id }}b">
                                    <label for="q{{ $q->id }}b">B. {{ $q->option_b }}</label>
                                </div>
                                <div class="option">
                                    <input type="radio" name="answers[{{ $q->id }}]" value="C" id="q{{ $q->id }}c">
                                    <label for="q{{ $q->id }}c">C. {{ $q->option_c }}</label>
                                </div>
                                <div class="option">
                                    <input type="radio" name="answers[{{ $q->id }}]" value="D" id="q{{ $q->id }}d">
                                    <label for="q{{ $q->id }}d">D. {{ $q->option_d }}</label>
                                </div>
                            </div>
                        @else
                            <textarea name="answers[{{ $q->id }}]" class="form-control" rows="4" placeholder="Jawaban Anda"></textarea>
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

        // anti refresh / close tab
        window.onbeforeunload = function(){
            return "Ujian sedang berlangsung! Pastikan submit sebelum keluar.";
        }
    </script>
</body>
</html>
