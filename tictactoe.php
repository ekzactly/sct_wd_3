<?php
session_start();

// Initialize the game board if it doesn't exist
if (!isset($_SESSION['board'])) {
    $_SESSION['board'] = array_fill(0, 9, '');
    $_SESSION['current_player'] = 'X';
}

// Handle player moves
if (isset($_POST['move'])) {
    $move = intval($_POST['move']);
    if ($_SESSION['board'][$move] == '') {
        $_SESSION['board'][$move] = $_SESSION['current_player'];
        $_SESSION['current_player'] = ($_SESSION['current_player'] == 'X') ? 'O' : 'X';
    }
}

// Check for a winner
function checkWinner($board) {
    $winningCombos = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8], // Rows
        [0, 3, 6], [1, 4, 7], [2, 5, 8], // Columns
        [0, 4, 8], [2, 4, 6] // Diagonals
    ];

    foreach ($winningCombos as $combo) {
        if ($board[$combo[0]] != '' &&
            $board[$combo[0]] == $board[$combo[1]] &&
            $board[$combo[0]] == $board[$combo[2]]) {
            return $board[$combo[0]];
        }
    }

    return false;
}

$winner = checkWinner($_SESSION['board']);

// Check for a draw
$isDraw = !in_array('', $_SESSION['board']) && !$winner;

// Reset the game
if (isset($_POST['reset'])) {
    $_SESSION['board'] = array_fill(0, 9, '');
    $_SESSION['current_player'] = 'X';
    $winner = false;
    $isDraw = false;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tic-Tac-Toe</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #3498db; /* New attractive background color */
        }
        .game-container {
            background-color: #ecf0f1;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        .board {
            display: grid;
            grid-template-columns: repeat(3, 100px);
            gap: 10px;
            margin-bottom: 30px;
        }
        .cell {
            width: 100px;
            height: 100px;
            font-size: 2.5em;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ffffff;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #2c3e50;
        }
        .cell:hover {
            background-color: #bdc3c7;
        }
        .cell[disabled] {
            cursor: not-allowed;
            opacity: 0.7;
        }
        .status {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.4em;
            font-weight: bold;
            color: #2c3e50;
        }
        .reset-btn {
            display: block;
            width: 100%;
            padding: 15px;
            font-size: 1.2em;
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .reset-btn:hover {
            background-color: #27ae60;
        }
    </style>
</head>
<body>
    <div class="game-container">
        <h1>Tic-Tac-Toe</h1>
        
        <div class="status">
            <?php
            if ($winner) {
                echo "Player $winner wins!";
            } elseif ($isDraw) {
                echo "It's a draw!";
            } else {
                echo "Current player: " . $_SESSION['current_player'];
            }
            ?>
        </div>

        <form method="post" class="board">
            <?php for ($i = 0; $i < 9; $i++): ?>
                <button type="submit" name="move" value="<?php echo $i; ?>" class="cell" <?php echo ($winner || $isDraw || $_SESSION['board'][$i] != '') ? 'disabled' : ''; ?>>
                    <?php echo $_SESSION['board'][$i]; ?>
                </button>
            <?php endfor; ?>
        </form>

        <form method="post">
            <button type="submit" name="reset" class="reset-btn">Reset Game</button>
        </form>
    </div>
</body>
</html>