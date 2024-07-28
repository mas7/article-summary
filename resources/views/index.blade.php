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

        .loading-animation {
            display: none;
            /* initially hidden */
            margin: 20px auto;
            width: 40px;
            height: 40px;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top-color: #333;
            animation: spin 1s ease-in-out infinite;
        }

        #summaryText {
            line-height: 2vw;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <h1>Article Summarizer</h1>
    <form action="{{ route('summarize') }}" method="POST" data-summarize-url="{{ route('summarize') }}">
        @csrf
        <label for="url">Enter the URL of the article to summarize:</label>
        <input type="text" id="url" name="url" required>
        <button type="submit">Summarize</button>
    </form>


    <div class="loading-animation"></div>

    <div class="summary" style="display: none;">
        <h2>Summary</h2>
        <p id="summaryText"></p>
    </div>

    <script>
        const form = document.querySelector('form');
        const loadingAnimation = document.querySelector('.loading-animation');
        const summaryElement = document.querySelector('.summary');
        const summaryTextElement = document.getElementById('summaryText');

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            loadingAnimation.style.display = 'block'; // Show loading animation
            summaryElement.style.display = 'none'; // Hide summary element during load

            const formData = new FormData(form);
            const url = form.getAttribute('data-summarize-url'); // Get the URL from the data attribute

            fetch(url, {
                    method: 'POST',
                    body: formData,
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    displayTypingEffect(data.summary);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError(); // Handle errors by hiding loading and possibly displaying an error message
                });
        });

        function displayTypingEffect(text) {
            loadingAnimation.style.display = 'none'; // Ensure loading animation is hidden after typing
            summaryElement.style.display = 'block'; // Show summary element

            let index = 0;
            if (!text || text.length === 0) {
                showError("No summary available."); // Handle empty summaries
                return;
            }

            function typeCharacter() {
                if (index < text.length) {
                    summaryTextElement.textContent += text.charAt(index);
                    index++;
                    setTimeout(typeCharacter, 15); // Adjust typing speed here
                } else {
                    loadingAnimation.style.display = 'none'; // Ensure loading animation is hidden after typing
                    summaryElement.style.display = 'block'; // Show summary element
                }
            }
            typeCharacter();
        }

        function showError(message = "An error occurred.") {
            loadingAnimation.style.display = 'none'; // Hide loading animation on error
            summaryTextElement.textContent = message; // Optional: Display error message
            summaryElement.style.display = 'block'; // Show summary element even on error for message visibility
        }
    </script>


</body>

</html>