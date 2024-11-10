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
    <title>Cute Tic-Tac-Toe</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #9b59b6;
            --secondary-color: #8e44ad;
            --background-color: #f3e5f5;
            --text-color: #4a0e4e;
            --cell-color: #e1bee7;
            --hover-color: #d1c4e9;
        }

        body {
            font-family: 'Fredoka One', cursive;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: var(--background-color);
        }

        .game-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(155, 89, 182, 0.3);
        }

        h1 {
            text-align: center;
            color: var(--primary-color);
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
            background-color: var(--cell-color);
            border: none;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--text-color);
        }

        .cell:hover {
            background-color: var(--hover-color);
            transform: scale(1.05);
        }

        .cell[disabled] {
            cursor: not-allowed;
            opacity: 0.7;
        }

        .status {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.4em;
            color: var(--text-color);
        }

        .reset-btn {
            display: block;
            width: 100%;
            padding: 15px;
            font-size: 1.2em;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Fredoka One', cursive;
        }

        .reset-btn:hover {
            background-color: var(--secondary-color);
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(142, 68, 173, 0.3);
        }

        .player-icon {
            font-size: 2em;
        }
    </style>
</head>
<body>
    <div class="game-container">
        <h1><i class="fas fa-gamepad"></i> Cute Tic-Tac-Toe</h1>
        
        <div class="status">
            <?php
            if ($winner) {
                echo "Player " . ($winner == 'X' ? '<i class="fas fa-cat player-icon"></i>' : '<i class="fas fa-dog player-icon"></i>') . " wins!";
            } elseif ($isDraw) {
                echo "It's a draw! <i class=\"fa fa-handshake\"></i>";
            } else {
                echo "Current player: " . ($_SESSION['current_player'] == 'X' ? '<i class="fas fa-cat player-icon"></i>' : '<i class="fas fa-dog player-icon"></i>');
            }
            ?>
        </div>

        <form method="post" class="board">
            <?php for ($i = 0; $i < 9; $i++): ?>
                <button type="submit" name="move" value="<?php echo $i; ?>" class="cell" <?php echo ($winner || $isDraw || $_SESSION['board'][$i] != '') ? 'disabled' : ''; ?>>
                    <?php
                    if ($_SESSION['board'][$i] == 'X') {
                        echo '<i class="fas fa-cat"></i>';
                    } elseif ($_SESSION['board'][$i] == 'O') {
                        echo '<i class="fas fa-dog"></i>';
                    }
                    ?>
                </button>
            <?php endfor; ?>
        </form>

        <form method="post">
            <button type="submit" name="reset" class="reset-btn">
                <i class="fas fa-redo-alt"></i> Reset Game
            </button>
        </form>
    </div>
</body>
</html>
