{{--
  Template Name: Instructor Template
--}}
<?php

session_start();

use Orhanerday\OpenAi\OpenAi;

$open_ai_key = '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';
$open_ai = new OpenAi($open_ai_key);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_history'])) {
    // Clear the chat history
    $_SESSION['messages'] = [['role' => 'system', 'content' => 'Si Inštruktor AI, osebni učitelj matematike. Tvoja edina naloga je učenje matematike, tako, da ne odgovarjaj na vprašanja, ki niso povezana z matematiko. Učenca vodiš čez postopek reševanja problema in ga med tem sprašuješ vprašanja, da vidiš kako on razmišlja in mu pomagaš, da lažje razume problem. Govoriš izključno v slovenščini.']];
}

if (!isset($_SESSION['messages'])) {
    $_SESSION['messages'] = [['role' => 'system', 'content' => 'Si Inštruktor AI, osebni učitelj matematike. Tvoja edina naloga je učenje matematike, tako, da ne odgovarjaj na vprašanja, ki niso povezana z matematiko. Učenca vodiš čez postopek reševanja problema in ga med tem sprašuješ vprašanja, da vidiš kako on razmišlja in mu pomagaš, da lažje razume problem. Govoriš izključno v slovenščini.']];
}

$user_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_message'])) {
    $user_message = $_POST['user_message'];
    

    if (!empty($user_message)) {
        // Add the user's message to the session
        $_SESSION['messages'][] = ['role' => 'user', 'content' => $user_message];
    }

    // Limit the conversation history to the last N messages
    $N = 10;
    $messages = array_slice($_SESSION['messages'], -$N);

    $chat = $open_ai->chat([
        'model' => 'gpt-3.5-turbo-0613',
        'messages' => $messages,
        'temperature' => 1.0,
        'max_tokens' => 1000,
        'frequency_penalty' => 0,
        'presence_penalty' => 0,
    ]);

    // decode response
    $d = json_decode($chat);

    // Get Content
    $ai_response = $d->choices[0]->message->content;

    if (!empty($ai_response)) {
        // Store the AI's response in the session
        $_SESSION['messages'][] = ['role' => 'assistant', 'content' => $ai_response];
    }
}
?>
@extends('layouts.app')

@section('content')

<div class="general-content-page" style="background-color:#1a1a1a; padding-top:80px;padding-bottom:80px;">
    <div class="inner-child narrow">

        <div style="display: flex; flex-direction: column; justify-content:flex-end; height: 100vh;">
            <div style="overflow: auto; padding: 10px;">
                <?php if (isset($_SESSION['messages'])): ?>
                    <?php foreach ($_SESSION['messages'] as $message): ?>
                        <?php if ($message['role'] !== 'system'): ?>
                            <div style="display:flex; justify-content: <?php echo $message['role'] === 'user' ? 'flex-end' : 'flex-start'; ?>" class=" <?php echo $message['role'] === 'user' ? 'user-message' : 'assistant-message'; ?>">
                                <div style = "background-color:<?php echo $message['role'] === 'user' ? 'transparent' : '#3C3F53'; ?>; padding:18px;margin-bottom:24px;width:fit-content;color:#fff; border-style:solid; border-width:2.62px;border-color:#484a57;"> 
                                <?php 
                                    // Split the response into lines
                                    $lines = explode("\n", $message['content']);
                                    foreach ($lines as $line) {
                                        // Check if the line is a header
                                        if (strpos($line, '#### ') === 0) {
                                            echo '<h4 style="margin: 10px 0;color:#fff;">' . substr($line, 5) . '</h4>';
                                        } else if (strpos($line, '```') === 0) {
                                            // Check if the line is a code block
                                            echo '<p color: #fff; margin: 0;">';
                                            echo substr($line, 3, -3);
                                            echo '</p>';
                                        } else {
                                            // Otherwise, it's a normal line of text
                                            echo '<p style="margin: 0; color:#fff;">' . $line . '</p>';
                                        }
                                    }
                                ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <form method="POST" style="padding: 10px; display: flex;">
                <input type="text" id="user_message" name="user_message" value="" style="flex-grow: 1; padding: 10px; border: none; border-radius: 8px; background-color: #3C3F53; color: white;">
                <button type="submit" style="cursor:pointer;border: none; background: none; margin-left: -40px;"><svg style="height:16px;width:16px;margin:0;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="none" stroke-width="2"><path d="M.5 1.163A1 1 0 0 1 1.97.28l12.868 6.837a1 1 0 0 1 0 1.766L1.969 15.72A1 1 0 0 1 .5 14.836V10.33a1 1 0 0 1 .816-.983L8.5 8 1.316 6.653A1 1 0 0 1 .5 5.67V1.163Z" fill="currentColor"></path></svg></button>
            </form>
            <form method="POST" style="padding: 10px; display: flex;">
                <input type="hidden" id="clear_history" name="clear_history" value="1">
                <button type="submit" style="cursor:pointer;border: none;border-radius:8px; background-color: white; padding: 12px;">Počisti zgodovino pogovora</button>
            </form>
        </div>
    </div>
</div>



@endsection