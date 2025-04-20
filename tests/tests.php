<?php

require_once __DIR__ . '/testframework.php';

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../modules/database.php';
require_once __DIR__ . '/../modules/page.php';

$tests = new TestFramework();

// test 1: check database connection
$tests->add('Database connection', function() {
    global $config;
    try {
        $db = new Database($config['db']['path']);
        return assertExpression(true, "Connected to DB");
    } catch (Exception $e) {
        return assertExpression(false, "", $e->getMessage());
    }
});

// test 2: test count method
$tests->add('table count', function() {
    global $config;
    $db = new Database($config['db']['path']);
    $count = $db->Count('page');
    return assertExpression($count >= 3, "Count >= 3", "Count less than expected");
});

// test 3: test create method
$tests->add('data create', function() {
    global $config;
    $db = new Database($config['db']['path']);
    $id = $db->Create('page', ['title' => 'Test title', 'content' => 'Test content']);
    return assertExpression(is_numeric($id), "Record created", "Create failed");
});

// test 4: test read method
$tests->add('data read', function() {
    global $config;
    $db = new Database($config['db']['path']);
    $data = $db->Read('page', 1);
    return assertExpression(isset($data['title']), "Read successful", "Read failed");
});

// test 5: page rendering
$tests->add('template render', function() {
    $page = new Page(__DIR__ . '/../templates/index.tpl');
    $html = $page->Render(['title' => 'Test', 'content' => 'Example']);
    return assertExpression(strpos($html, 'Test') !== false, "Render OK", "Render failed");
});

$tests->run();

echo $tests->getResult();
