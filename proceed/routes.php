<?php
function proceedReadUrl() {
    // first we remove the query string variables from the url (those are still available from $_GET)
    $url = proceedRemoveQueryStringVariables();
    // then we see if there is a match for the url
    $match = proceedMatchUrlToFile($url);
    if($match === false) {
        $url_components = explode('/',$url);
        $match = proceedInterpretUrl($url_components);      
    }
    return $match;
}

function proceedInterpretUrl($url_components = array()) {
    $matched = array(
        'file' => array(),
        'action' => '',
        'params' => array()
    );

    // just return an empty $matched if no url components are received
    if(empty($url_components)) {
        return $matched;
    }

    $file_location = PUBLIC_PATH;
    foreach($url_components as $component) {

        // first we check if the element is pointing to a directory inside PUBLIC_PATH
        if(is_dir($file_location.$component) && is_array($matched['file'])) {
            $matched['file'][] = $component;
            $file_location .= $component.DIRECTORY_SEPARATOR;
        }

        // then we check if the the element is pointing to a file
        elseif(is_array($matched['file'])) {
            if(file_exists($file_location.$component.'.php')) {
                $matched['file'] = trim(implode(DIRECTORY_SEPARATOR, $matched['file']).DIRECTORY_SEPARATOR.$component.'.php', DIRECTORY_SEPARATOR);
            }
            else {
                $matched['file'] = '404.php';
            }
        }

        // then, if we've created the file path string...
        elseif(is_string($matched['file']) && strlen($matched['file']) > 0) {
            // ...we decide that the next segment is going to be either the function defined in the file...
            if(strlen($matched['action']) === 0) {
                $matched['action'] = $component;
            }
            // ...or one of the parameters if the function is already defined
            else {
                $matched['params'][] = $component;
            }
        }
    }
    
    // if we didn't get to the file already, we will assume that
    if(is_array($matched['file']) && !empty($matched['file'])) {
        $matched['file'] = trim(implode(DIRECTORY_SEPARATOR, $matched['file']).DIRECTORY_SEPARATOR.'index.php', DIRECTORY_SEPARATOR);
        
        // if the file doesn't exist, we propose a "404.php" as file
        if(!file_exists(PUBLIC_PATH.$matched['file'])) {
            $matched['file'] = '404.php';
        }
    }

    return $matched;
}

function proceedRemoveQueryStringVariables()
{
    $url = $_SERVER['QUERY_STRING'];
    if ($url != '') {
        $parts = explode('&', $url, 2);
        if(strpos($parts[0], '=') === false) {
            $url = $parts[0];
        }
        else {
            $url = '';
        }
    }
    return $url;
}

function proceedMatchUrlToFile($url)
{
    $routes = array(
        'TEST/REST' => array(
            'file' => 'home.php',
            'action'=>'index',
            'params' => array(),
            'routeName' => 'home'),
        'posts' => array(
            'file' => 'posts.php',
            'action'=>'index',
            'params' => array(),
            'routeName' => 'allPosts'),
    );
    if(!empty($routes) && strlen($url) > 0) {
        foreach($routes as $route => $params) {
            if (strcmp($route, $url) === 0) {
                return $params;
            }
        }
    }
    return false;
}