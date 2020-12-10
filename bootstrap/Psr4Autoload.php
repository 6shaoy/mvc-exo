<?php

class Psr4Autoload
{

    //namespace maps
    private $maps = [];

    public function __construct()
    {
        spl_autoload_register([$this, 'autoload']);
    }

    public function autoload($className)
    {
        $pos = strrpos($className, "\\");
        $namespace = substr($className, 0, $pos);
        $class = substr($className, $pos + 1);

        $this->mapLoad($namespace, $class);
    }

    private function mapLoad($namespace, $class)
    {
        if (array_key_exists($namespace, $this->maps)) {
            $namespace = $this->maps[$namespace];
        } else {
            die('NAMESPACE no mapped yet');
        }

        $namespace = rtrim(str_replace('\\', '/', $namespace), '/') . '/';

        $filePath = $namespace . $class . '.php';

        if (file_exists($filePath)) {
            include $filePath;
        } else {
            die('no class file:' . $filePath);
        }
    }

    public function add($namespace, $path)
    {
        if (array_key_exists($namespace, $this->maps)) {
            die('namespace exists');
        }

        $this->maps[$namespace] = $path;
    }

}
