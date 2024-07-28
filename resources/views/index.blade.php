<!DOCTYPE html>
<html>

<head>
    <title>Article Summarizer</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 700px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fafafa;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1,
        h2 {
            text-align: center;
            color: #333;
        }

        form {
            margin: 30px auto;
            text-align: center;
            width: 90%;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #555;
            font-size: 18px;
            font-weight: normal;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            margin-top: 5px;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            transition: border 0.3s;
        }

        input[type="text"]:focus {
            border-color: #66afe9;
            outline: none;
        }

        button[type="submit"] {
            background-color: #5cb85c;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        button[type="submit"]:hover {
            background-color: #4cae4c;

        }

        .summary {
            margin-top: 30px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: monospace;
            position: relative;
            overflow: hidden;
            word-wrap: break-word;
        }

        .typing-animation {
            line-height: 160%;
            color: #333;
            font-size: 16px;
        }

        .caret {
            display: inline-block;
            width: 2px;
            height: 1em;
            background-color: #333;
            vertical-align: bottom;
            animation: blink-caret .75s step-end infinite;
        }

        @keyframes blink-caret {

            from,
            to {
                background-color: transparent;
            }

            50% {
                background-color: #333;
            }
        }
    </style>
</head>

<body>
    <h1>Article Summarizer</h1>
    <form action="{{ route('summarize') }}" method="POST">
        @csrf
        <label for="url">Enter the URL of the article to summarize:</label>
        <input type="text" id="url" name="url" required>
        <button type="submit">Summarize</button>
    </form>

    @if(isset($summary))
    <div class="summary">
        <h2>Summary</h2>
        <p class="typing-animation" id="summaryText"></p>
    </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const summaryElement = document.getElementById('summaryText');
            const summaryText = `{{ $summary }}`;
            const caret = document.createElement('span');
            caret.className = 'caret';
            summaryElement.appendChild(caret);

            let i = 0;

            function typeWriter() {
                if (i < summaryText.length) {
                    caret.before(summaryText.charAt(i));
                    i++;
                    setTimeout(typeWriter, 10);
                } else {
                    caret.style.animation = 'none';
                }
            }
            typeWriter();
        });
    </script>
</body>

</html>