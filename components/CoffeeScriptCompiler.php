<?php
/**
 * CofeeScriptCompiler class file.
 * @author Anthony Burton <apburton84@googlemail.com>
 * @copyright Copyright &copy; Anthony Burton  2012 -
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version 0.10.0
 */

// FIXME - Do we need to include all file ? 
// foreach (glob(dirname(__FILE__). "/../lib/coffeescript-php/src/CoffeeScript/*.php") as $filename) {
//    include $filename;
// }

require dirname(__FILE__) .  '/../lib/coffeescript-php/src/CoffeeScript/Init.php';
CoffeeScript\Init::load(); 

class CoffeeScriptCompiler extends CApplicationComponent
{
    /**
     * @var string base path.
     */
    public $basePath;
    /**
     * @var array paths to process.
     */
    public $paths = array();
    /**
     * @var boolean indicates whether to always compile all files.
     */
    public $forceCompile = false;
    /**
     * @var CoffeeScript compiler instance.
     */
    protected $_parser;
    /**
     * @var Log Trace of CoffeeScript compile to file.
     */
    public $trace = false;
  
    /**
     * Initializes the component.
     * @throws CException if the base path does not exist.
     */
    public function init()
    {
        if (!isset($this->basePath))
            $this->basePath = Yii::getPathOfAlias('webroot');

        if (!file_exists($this->basePath))
            throw new CException(__CLASS__.': Failed to initialize compiler. Base path does not exist!');

        if (!is_dir($this->basePath))
            throw new CException(__CLASS__.': Failed to initialize compiler. Base path is not a directory!');

        if (!isset($this->trace))
            $this->options['trace'] = true;

        if ($this->compile || $this->hasChanges())
            $this->compileAll();
    }

    /**
     * Compiles a CoffeeScript file to a Javascript file.
     * @param string $from the path to the source CoffeeSript file(s).
     * @param string $to the path to the target Javascript file.
     * @throws CException if the compilation fails or the source path does not exist.
     */
    public function compile($from, $to)
    {
        if (is_dir($from))
        {
            throw new CException(__CLASS__ . ': Failed to compile coffeescript, file is a directory');
        } 

        if (file_exists($from))
        {
            try
            {
                $coffee = file_get_contents($from);
                $js     = CoffeeScript\Compiler::compile($coffee, $this->options);
                
                file_put_contents($to, $js, FILE_APPEND | LOCK_EX);

                $cs = Yii::app()->getClientScript();
                $cs->registerScriptFile($this->baseUrl . '/' . $to, CClientScript::POS_END);            
            }
            catch (Exception $e)
            {
                throw new CException(__CLASS__.': Failed to compile coffescipt file with message: '.$e->getMessage().'.');
            }

        }
        else
            throw new CException(__CLASS__.': Failed to coffeescript file. Source path does not exist!');
    }

    /**
     * Compiles all CoffeeScript files.
     */
    protected function compileAll()
    {
        foreach ($this->paths as $coffeePath => $jsPath)
        {
            $from = $this->basePath . '/' . $coffeePath;
            $to   = $jsPath;
            
            $this->compile($from, $to);
        }
    }

    /**
     * Returns whether any of files configured to be compiled has changed.
     * @return boolean the result.
     */
    protected function hasChanges()
    {
        $dirs = array();
        foreach ($this->paths as $source => $destination)
        {
            $compiled = $this->getLastModified($destination);
            if (!isset($lastCompiled) || $compiled < $lastCompiled)
                $lastCompiled = $compiled;

            if (!in_array(dirname($source), $dirs))
                $dirs[] = $source;
        }

        foreach ($dirs as $dir) {
            $modified = $this->getLastModified($dir);
            if (!isset($lastModified) || $modified < $lastModified)
                $lastModified = $modified;
        }

        return isset($lastCompiled) && isset($lastModified) && $lastModified > $lastCompiled;
    }

    /**
     * Returns the last modified for a specific path.
     * @param string $path the path.
     * @return integer the last modified (as a timestamp).
     */
    protected function getLastModified($path)
    {
        if (!file_exists($path))
            return 0;
        else
        {
            if (is_file($path))
            {
                $stat = stat($path);
                return $stat['mtime'];
            }
            else
            {
                $lastModified = null;

                /** @var Directory $dir */
                $dir = dir($path);
                while ($entry = $dir->read())
                {
                    if (strpos($entry, '.') === 0)
                        continue;

                    $path .= '/'.$entry;

                    if (is_dir($path))
                        $modified = $this->getLastModified($path);
                    else
                    {
                        $stat = stat($path);
                        $modified = $stat['mtime'];
                    }

                    if (isset($lastModified) || $modified > $lastModified)
                        $lastModified = $modified;
                }

                return $lastModified;
            }
        }
    }
}
