<?php
include_once 'vendor/autoload.php';
include_once 'google.php';

//menampung variabel rawtext
$rawtext ="";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $rawtext = test_input($_POST["rawtext"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//mengambil input kata ke google translate
$AmbilKalimat = $rawtext;
$SourceLang ='id'; // asal kata
$TransLang = 'en'; // di terjemahkan ke
$data = DiTranslate($AmbilKalimat,$SourceLang,$TransLang);
$DecodeJson = json_decode($data,true);

//use Sentiment-Analyzer
Use Sentiment\Analyzer;
$analyzer = new Analyzer();
$result = $analyzer->getSentiment($DecodeJson["sentences"][0]["trans"]);

//menentukan nilai sentimen bedasarkan nilai compound lihat : https://github.com/cjhutto/vaderSentiment#about-the-scoring
$CompoundScore = $result['compound'];
if($CompoundScore >= 0.05 ){
    $CompoundScores='positive';
}elseif($CompoundScore > -0.05 || $result < 0.05){
    $CompoundScores='Neutral';
}elseif($CompoundScore <= -0.05){
    $CompoundScores='Negative';
};
?>
<html>
<head>
    <title>Application Analisa-Sentimen</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/all.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <style type="text/css">
        body {
            font: 15px/1.5 Arial, Halvetica, sans-serif;
        }
        
        @media (min-width:768px) {
            /* centered navigation */
            .nav.navbar-nav {
                float: left;
            }
            .nav.navbar-nav {
                clear: left;
                float: left;
                margin: 0;
                padding: 0;
                position: relative;
                left: 50%;
                text-align: center;
            }
            .nav.navbar-nav > li {
                position: relative;
                right: 50%;
            }
            .nav.navbar-nav li {
                text-align: left
            }
        }
        
        .spinner-1::before {
            content: "";
            box-sizing: border-box;
            position: absolute;
            top: 50%;
            left: 50%;
            height: 60px;
            width: 60px;
            margin-top: -30px;
            margin-left: -30px;
            border-radius: 50%;
            border: 6px solid transparent;
            border-top-color: red;
            animation: spinner 1s linear infinite;
        }
        
        @keyframes spinner {
            to {
                transform: rotate(360deg);
            }
        }
        
        li {
            background-color: red;
        }
        
        li:nth-child(odd) {
            background-color: red;
        }
    </style>
    <div class="container">
        <div style="background:tomato !important; color:whitesmoke;" class="jumbotron text-center">
            <h1>Aplikasi Analisa Sentimen PHP Indonesia</h1>
            <h3>Menggunakan VADER & Compound Score</h3>
            <br>
            <nav class="navbar navbar-nav navbar-light bg-light">
                <div class="text-center">
                    <a class="navbar-brand navbar-text" href="/">Home</a>
                    <a class="navbar-brand navbar-text" href="http://www.youtube.com/channel/UCiIfsCQnKX5Av57LyyANJPQ?sub_confirmation=1">Youtube Channel</a>
                </div>
            </nav>
        </div>
    </div>
    <div class="container">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="myForm">
            <div class="card">
                <div class="card-body text-center">
                    <label>Teks Input</label>
                    <textarea id="TweetName" class="form-control text-center" rows="3" cols="2" name="rawtext" placeholder="..Input text.."></textarea>
                    <br>
                    <div>
                        <input type="submit" onclick="myAnalyser()" value="Analysis" class="btn btn-primary ">
                        <a href="/" type="button" class="btn btn-danger"> Reset</a>
                        <input type="button" onclick="myFunction()" value="Delete" class="btn btn-outline-dark">
                    </div>
                    <label style="color: red;font-family: sans-serif;size: 20px"></label>
                </div>
            </div>
        </form>
    </div>
    <?php
        
	if($AmbilKalimat==NULL){
     
	}else{

    echo '
    <hr>
    <div class="main">
    <div class="container">
        <div class="card">
            <div class="card-body text-center">
                <!-- <p class="card-title"><div class="alert alert-primary" role="alert"></p> -->
                <p>Text Entered :</p>
                <p style="color:red;font-family: sans-serif;">'.$DecodeJson["sentences"][0]["orig"].'</p>
                <hr>
                <p>Translate Text to English :</p>
                <p style="color:red;font-family: sans-serif;">'.$DecodeJson["sentences"][0]["trans"].'</p>
                <p>Word Weight :</p>
                <p style="color:red;font-family: sans-serif;"></p>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Category :</th>
                        <th scope="col">Vader Scores</th>
                        <th scope="col">Compound</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">Result :</th>
                        <th scope="row">';?>
                        <?php
                        //sort array
                        arsort($result);
                        $KEYMap = array_keys($result);
                        echo $KEYMap[0];
                        ?>
                        <?php echo '</th>
                        <th scope="row">'.$CompoundScores. '</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>';
	}; 
?>
    <p class="text-center">© Copyright <?php echo date('Y');?> by <a href="//www.youtube.com/channel/UCiIfsCQnKX5Av57LyyANJPQ" rel="noopener noreferrer" target="_blank">Belajar IT</a> |Theme BY <a href="https://id.linkedin.com/in/achmad-bayhaqy" rel="noopener noreferrer" target="_blank">Achmad Bayhaqy</a></p>

    <script src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script>
        function myFunction() {
            document.getElementById('myForm').reset();
        }
    </script>
    <script>
        function myAnalyser() {
            document.querySelector('.main div').style.display = 'none';
            document.querySelector('.main').classList.add('spinner-1');
            setTimeout(() => {
                document.querySelector('.main').classList.remove('spinner-1');
                document.querySelector('.main div').style.display = 'block';
            }, 5000);
        }
    </script>
    <script>
        $(document).ready(function() {
            $("#myForm").submit(function() {
                var isValid = true;
                if ($("#TweetName").val().trim().length == 0) {
                    isValid = false;
                    alert("Text column can't be empty");
                }
                return isValid;
            });
        });
    </script>
</body>
</html>