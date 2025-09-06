<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Tools</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            /* New Color Palette based on provided values */
            --primary-color: #1B5E6F; /* Dark Teal */
            --secondary-color: #4A9B4E; /* Green */
            --accent-orange: #F4965A; /* Orange from logo */
            --light-teal: #2A7A8A; /* Lighter teal variation */
            --soft-green: #5BAF5F; /* Softer green */
            --warm-orange: #FF8C42; /* Warmer orange */
            
            --card-bg: rgba(255, 255, 255, 0.2);
            --border-color: rgba(255, 255, 255, 0.3);
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-image: linear-gradient(135deg, var(--primary-color) 0%, var(--light-teal) 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: #fff;
        }

        .main-container {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid var(--border-color);
            box-shadow: 0 8px 32px 0 var(--shadow-color);
            padding: 3rem;
            width: 90%;
            max-width: 550px;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        .nav-menu {
            margin-bottom: 2rem;
            display: flex;
            justify-content: center;
            gap: 1.5rem;
        }
        
        .nav-menu button {
            background-color: transparent;
            border: 2px solid #fff;
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .nav-menu button.active, .nav-menu button:hover {
            background-color: #fff;
            color: var(--accent-orange);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .tool-section {
            display: none;
            animation: fadeIn 0.5s ease-in-out;
        }

        .tool-section.active {
            display: block;
        }

        h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.1);
        }

        .input-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .input-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #eee;
        }

        .input-group input {
            width: 100%;
            padding: 0.9rem;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 1rem;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }
        
        .input-group input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .input-group input:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
            outline: none;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
        }

        .submit-button {
            width: 100%;
            padding: 1.2rem;
            background-color: #fff;
            color: var(--primary-color);
            border: none;
            border-radius: 12px;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .submit-button:hover {
            background-color: #f0f0f0;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
        
        #predictionResult {
            margin-top: 2rem;
            font-size: 1.5rem;
            font-weight: 600;
            color: #fff;
        }

        #totalMarks {
            margin-top: 2.5rem;
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            padding: 1rem 1.5rem;
            background: var(--card-bg);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            box-shadow: inset 0 0 10px rgba(255, 255, 255, 0.1);
            display: none;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="main-container">
    <div class="nav-menu">
        <button id="predictBtn" class="active">Pass/Fail Predictor</button>
        <button id="calculateBtn">Marks Calculator</button>
    </div>

    <div id="predictorSection" class="tool-section active">
        <h2>Pass/Fail Predictor</h2>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type']) && $_POST['form_type'] == 'predictor') {
            $data = [
                "TotalMarks" => (int)$_POST["totalmarks"]
            ];
            $jsonData = json_encode($data);
            $ch = curl_init("http://localhost:5000/predict");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json",
                "Content-Length: " . strlen($jsonData)
            ]);
            $response = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($response, true);
            echo "<h3 id='predictionResult'>" . htmlspecialchars($result["prediction"]) . "</h3>";
        }
        ?>
        <form method="post">
            <input type="hidden" name="form_type" value="predictor">
            <div class="input-group">
                <label for="totalmarks">Enter Total Marks:</label>
                <input type="number" name="totalmarks" id="totalmarks" required>
            </div>
            <button type="submit" class="submit-button">Predict Pass/Fail</button>
        </form>
    </div>

    <div id="calculatorSection" class="tool-section">
        <h2>Student Marks Calculator</h2>
        <div class="input-group">
            <label for="maths">Maths:</label>
            <input type="number" id="maths" min="0" max="100" required>
        </div>
        <div class="input-group">
            <label for="english">English:</label>
            <input type="number" id="english" min="0" max="100" required>
        </div>
        <div class="input-group">
            <label for="sinhala">Sinhala:</label>
            <input type="number" id="sinhala" min="0" max="100" required>
        </div>
        <div class="input-group">
            <label for="science">Science:</label>
            <input type="number" id="science" min="0" max="100" required>
        </div>
        <div class="input-group">
            <label for="buddhism">Buddhism:</label>
            <input type="number" id="buddhism" min="0" max="100" required>
        </div>
        <div class="input-group">
            <label for="history">History:</label>
            <input type="number" id="history" min="0" max="100" required>
        </div>
        
        <button id="calcBtn" class="submit-button">Calculate Total</button>
        
        <div id="totalMarks"></div>
    </div>
</div>

<script>
    const predictBtn = document.getElementById('predictBtn');
    const calculateBtn = document.getElementById('calculateBtn');
    const predictorSection = document.getElementById('predictorSection');
    const calculatorSection = document.getElementById('calculatorSection');

    predictBtn.addEventListener('click', () => {
        predictorSection.classList.add('active');
        calculatorSection.classList.remove('active');
        predictBtn.classList.add('active');
        calculateBtn.classList.remove('active');
    });

    calculateBtn.addEventListener('click', () => {
        calculatorSection.classList.add('active');
        predictorSection.classList.remove('active');
        calculateBtn.classList.add('active');
        predictBtn.classList.remove('active');
    });

    document.getElementById('calcBtn').addEventListener('click', function() {
        const maths = parseInt(document.getElementById('maths').value) || 0;
        const english = parseInt(document.getElementById('english').value) || 0;
        const sinhala = parseInt(document.getElementById('sinhala').value) || 0;
        const science = parseInt(document.getElementById('science').value) || 0;
        const buddhism = parseInt(document.getElementById('buddhism').value) || 0;
        const history = parseInt(document.getElementById('history').value) || 0;
        
        const total = maths + english + sinhala + science + buddhism + history;
        
        const totalMarksDiv = document.getElementById('totalMarks');
        totalMarksDiv.textContent = `Total Marks: ${total}`;
        totalMarksDiv.style.display = 'block';
    });
</script>

</body>
</html>