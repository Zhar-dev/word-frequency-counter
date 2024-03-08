<?php

// Function to define a list of common stop words
function getStopWords() {
  return [
    "the", "and", "in", "a", "of", "to", "is", "on", "that", "it",
    "for", "an", "as", "with", "by", "or", "has", "be", "are", "but",
    "at", "which", "my", "one", "all", "would", "there", "their", "what",
    "so", "up", "out", "if", "about", "who", "get", "which", "go", "me",
    "when", "make", "can", "like", "time", "no", "just", "him", "know",
    "take", "people", "into", "year", "your", "good", "some", "could", "them",
    "see", "other", "than", "then", "now", "look", "only", "come", "its",
    "over", "think", "also", "back", "after", "use", "two", "how", "our",
    "work", "first", "well", "way", "even", "new", "want", "because", "any",
    "these", "give", "day", "most", "us"
  ];
}

// Function to tokenize text into words
function tokenizeText($text) {
  // Remove punctuation and convert to lowercase
  $text = preg_replace('/\p{P}+/', '', strtolower($text));
  // Split text into words by delimiters
  return explode(' ', $text);
}

// Function to calculate word frequencies
function calculateWordFrequency($tokens) {
  $stopWords = getStopWords();
  $frequencies = array_count_values(array_diff($tokens, $stopWords));
  return $frequencies;
}

// Function to sort words by frequency
function sortByFrequency($frequencies, $order = "DESC") {
  arsort($frequencies, $order === "DESC" ? SORT_DESC : SORT_ASC);
  return $frequencies;
}

// Handle user input and process data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $text = $_POST['text'];
  $sort_order = $_POST['sort_order'];
  $display_limit = (int) $_POST['display_limit'];

  if (empty($text)) {
    $error_message = "Please enter some text.";
  } else {
    // Process text
    $tokens = tokenizeText($text);
    $frequencies = calculateWordFrequency($tokens);
    $frequencies = sortByFrequency($frequencies, $sort_order);

    // Limit output based on display limit
    $frequencies = array_slice($frequencies, 0, $display_limit, true);
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Word Frequency Counter</title>
  <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
  <h1>Word Frequency Counter</h1>
  <?php if (isset($error_message)): ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php endif; ?>
  <form method="post">
    <textarea name="text" rows="10" cols="50" placeholder="Enter text here"></textarea><br>
    <label for="sort_order">Sort Order:</label>
    <select name="sort_order" id="sort_order">
      <option value="DESC">Descending</option>
      <option value="ASC">Ascending</option>
    </select><br>
    <label for="display_limit">Display Limit:</label>
    <input type="number" name="display_limit" min="1" value="10">
    <br><br>
    <button type="submit">Analyze</button>
  </form>
  <?php if (isset($frequencies)): ?>
    <h2>Results</h2>
    <p>Total words: <?php echo count($frequencies); ?></p>
    <ul>
      <?php foreach ($frequencies as $word => $count): ?>
        <li><?php echo "$word: $count"; ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</body>
</html>
