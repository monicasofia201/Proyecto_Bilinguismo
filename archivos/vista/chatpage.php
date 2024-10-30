<?php

    $sql = "SELECT * FROM `chat`";
    $query = mysqli_query($conn, $sql);

    if (!$query) {
        die("Error executing query: " . mysqli_error($conn));
    }
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <style>
        :root {
            --alternate-text: #919191;
            --primary-background: #fff;
            --secondary-background: #E6E6E6;
            --alternate-background: #04324d;
        }

        ::selection {
            color: #fff;
            background: #fd7d1c;
        }

        ::-webkit-scrollbar {
            width: 3px;
            border-radius: 25px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #ddd;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #ccc;
        }

        .wrapper {
            position: fixed;
            bottom: 10px;
            left: 10px;
            width: 70px;
            height: 35px;
            transition: transform 0.3s ease-in-out, width 0.3s ease-in-out, height 0.3s ease-in-out;
            transform: translateY(calc(100% - 30px));
            background-color: var(--alternate-background);
            border-radius: 5px;
            border: 2px solid black;
            z-index: 10000;
        }

        .wrapper:hover {
            transform: translateY(0%);
            width: 380px;
            height: 500px;
        }

        .wrapper .title {
            background-color: var(--alternate-background);
            color: #fff;
            font-size: 20px;
            font-weight: 500;
            line-height: 36px;
            text-align: center;
            border-bottom: 1px solid rgba(0, 123, 255, 0.8);
            border-radius: 5px 5px 0 0;
        }

        .wrapper .form,
        .wrapper .typing-field {
            display: none;
        }

        .wrapper:hover .form,
        .wrapper:hover .typing-field {
            display: block;
        }

        .wrapper .form {
            padding: 20px 15px;
            height: 353px;
            margin-bottom: -5%;
            overflow-y: auto;
            background-color: var(--primary-background);
            border-radius: 5px;
            top: 50px;
            left: 50px;
        }

        .wrapper .form .inbox {
            width: 100%;
            display: flex;
            align-items: baseline;
        }

        .wrapper .form .user-inbox {
            justify-content: flex-end;
            margin: 1rem;
        }

        .wrapper .form .inbox .icon {
            height: 40px;
            width: 40px;
            text-align: center;
            line-height: 40px;
            border-radius: 50%;
            font-size: 18px;
        }

        .wrapper .form .inbox .msg-header {
            max-width: 53%;
            margin-left: 10px;
        }

        .form .inbox .msg-header p {
            color: #fff;
            background: rgba(0, 123, 255, 0.8);
            border-radius: 10px;
            padding: 8px 10px;
            font-size: 14px;
            word-break: break-all;
        }

        .form .user-inbox .msg-header p {
            color: #333;
            background: #efefef;
        }

        .wrapper .typing-field .input-data {
            height: 64px;
            width: 367px;
            position: absolute;

            left: 5px;
        }

        .wrapper .typing-field .input-data input {
            height: 100%;
            width: 100%;
            outline: none;
            border: 1px solid transparent;
            padding: 0 80px 0 15px;
            border-radius: 3px;
            font-size: 15px;
            background: #fff;
            transition: all 0.3s ease;
        }

        .typing-field .input-data input:focus {
            border-color: rgba(0, 123, 255, 0.8);
        }

        .input-data input::placeholder {
            color: #999999;
            transition: all 0.3s ease;
        }

        .input-data input:focus::placeholder {
            color: #bfbfbf;
        }

        .wrapper .typing-field .input-data button {
            position: absolute;
            right: 5px;
            top: 50%;
            height: 30px;
            width: 65px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            outline: none;
            opacity: 0;
            pointer-events: none;
            border-radius: 3px;
            background: var(--alternate-background);
            border: 1px solid var(--alternate-background);
            transform: translateY(-50%);
            transition: all 0.3s ease;
        }

        .wrapper .typing-field .input-data input:valid~button {
            opacity: 1;
            pointer-events: auto;
        }


        .display-chat {
            height: 337px;
            margin-bottom: 4%;
            overflow: auto;
            padding: 26px;
            border-radius: 5px;
            background-color: var(--primary-background);
        }

        .message {
            background-color: var(--secondary-background);
            color: var(--alternate-text);
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="title">Chat</div>
        <div class="form">
            <div class="display-chat" id="display-chat">
                <?php
                if(mysqli_num_rows($query) > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                ?>
                <div class="message">
                    <p>
                        <span><?php echo htmlspecialchars($row['name']); ?> :</span>
                        <?php echo htmlspecialchars($row['message']); ?>
                    </p>
                </div>
                <?php
                    }
                } else {
                ?>
                <div class="message">
                    <p>No hay ninguna conversación previa.</p>
                </div>
                <?php
                } 
                ?>
            </div>

            <form class="typing-field" method="post" action="sendMessage.php">
                <div class="input-data">
                    <input type="text" name="msg" placeholder="Ingresa tu mensaje acá..." required>
                    <button type="submit">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>