<?php

namespace framework;

class Tpl
{
    //path temple files
    private $viewDir = './view';
    //path cache files
    private $cacheDir = './cache';
    //expire time
    private $lifeTime = 3600;
    //array display vars
    private $vars = [];

    function __construct($viewDir = null, $cacheDir = null, $lifeTime = null)
    {
        if (!is_null($viewDir)) {
            if ($this->checkDir($viewDir)){
                $this->viewDir = $viewDir;
            }
        }

        if (!is_null($cacheDir)) {
            if ($this->checkDir($cacheDir)){
                $this->cacheDir = $cacheDir;
            }
        }

        if (!is_null($lifeTime)) {
            $this->viewDir = $viewDir;
        }
    }

    /**
     * $tpl->assign('title', $title);
     */
    function assign($k, $v)
    {
        $this->vars[$k] = $v;
    }

    /**
     * Undocumented function
     *
     * @param [type] $viewName
     * @param boolean $isInclude
     * @param [type] $uri           index.php?page=1
     * @return void
     */
    function display($viewName, $isInclude = true, $uri = null)
    {
        $viewPath = rtrim($this->viewDir, '/') . '/' . $viewName;
        if (!file_exists($viewPath)){
            die('template file doen\'t exist: ' . $viewPath);
        }

        $cachePath = rtrim($this->cacheDir, '/') . '/' . md5($viewName.$uri) . '.php';
        if (file_exists($cachePath)){
            $isTimeout = (filectime($cachePath) + $this->lifeTime) > time();
            $isChange = filemtime($viewPath) > filemtime($cachePath);
            if ($isTimeout || $isChange){
                $content = $this->compile($viewPath);
                file_put_contents($cachePath, $content);
            }
            
        } else {
            $content = $this->compile($viewPath);
            file_put_contents($cachePath, $content);
        }

        if ($isInclude) {
            extract($this->vars);
            include $cachePath;
        }
    }


    protected function checkDir($dir) 
    {
        if (!file_exists($dir) || !is_dir($dir)){
            return mkdir($dir, 0755, true);
        }
        if (!is_writable($dir) || !is_readable($dir)){
            return chmod($dir, 0755);
        }
        return true;
    }

    protected function compile($filePath)
    {
        $html = file_get_contents($filePath);

        $arr = [
            '{$%%}' => '<?php echo $\1; ?>',
            '{foreach %%}' => '<?php foreach (\1): ?>',
            '{/foreach}' => '<?php endForeach; ?>',
            '{include %%}' => 'include ',
            '{if %%}' => '<?php if (\1): ?>',
            '{/if}' => '<?php endif; ?>',
        ];

        // convert %% to .+
        foreach ($arr as $k=>$v) {
            $pattern = '#'.str_replace('%%', '(.+?)', preg_quote($k, '#')).'#';
            if (strstr($pattern, 'include')){
                $html = preg_replace_callback($pattern, [$this, 'parseInclude'], $html);
            } else {
                $html = preg_replace($pattern, $v, $html);
            }
        }

        return $html;
    }

    protected function parseInclude($data)
    {
        $fileName = trim($data[1], '\'"');
        $this->display($fileName, false);
        $cachePath = rtrim($this->cacheDir, '/') . '/' . md5($fileName) . '.php';
        return '<?php include "'. $cachePath .'" ?>';
    }
}