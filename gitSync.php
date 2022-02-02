<?php

    $url = ""; //?set url here
    $unzippedFileName = ""; //?set name of the downloaded file here e.g. 'repo-name-main'
    $backUpDirectoryName = "backup";

    function DeleteDir($dirPath, $exceptions = array()) 
    {
        if (! is_dir($dirPath)) {
            return;
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {

            //exceptions
            $foundException = false;
            if(is_array($exceptions))
            {
                for ($i=0; $i < count($exceptions); $i++) 
                { 
                    if($exceptions[$i] == $file)
                    {
                        $foundException = true;
                    }
                }
            }
            if($foundException)
            {
                continue;
            }

            if (is_dir($file)) {
                DeleteDir($file);
            } else {
                unlink($file);
            }
        }

        //exceptions
        $foundException = false;
        if(is_array($exceptions))
        {
            for ($i=0; $i < count($exceptions); $i++) 
            { 
                if($exceptions[$i] == $dirPath)
                {
                    $foundException = true;
                }
            }
        }
        if($foundException)
        {
            return;
        }

        rmdir($dirPath);
    }
    function SetPerms($dirPath)
    {
        if (! is_dir($dirPath)) 
        {
            return;
        }

        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') 
        {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) 
            {
                SetPerms($file);
            }
            else
            {
                chmod($file, 0777);
            }
        }

        chmod($dirPath, 0777);
    }
    function MoveAllFiles($from, $to, $exceptions = array(), $deleteFiles = false)
    {
        $files = scandir($from);
        $source = $from."/";
        $destination = $to."/";

        // Cycle through all source files
        foreach ($files as $file) 
        {
            if (in_array($file, array(".","..")) )  //|| !file_exists()
            {
                continue;
            }
            
            $foundException = false;
            if(is_array($exceptions))
            {
                for ($i=0; $i < count($exceptions); $i++) 
                { 
                    if($exceptions[$i] == $file)
                    {
                        $foundException = true;
                    }
                }
            }
            if($foundException)
            {
                continue;
            }

            //move files
            if(is_dir($source.$file))
            {
                rename($source.$file, $destination.$file); 
            }
            else
            {
                copy($source.$file, $destination.$file);
            }

            //delete files
            if($deleteFiles && $file != "gitSync.php" && $file != "gitSyncAuto.js")
            {
                if(file_exists($file))
                {
                    if(is_dir($file))
                    {
                        DeleteDir($file, array("gitSync.php", "gitSyncAuto.js"));
                    }
                    else
                    {                
                        unlink($file);
                    }
                }
            }
        }
    }
    
    //create new backup
    if (is_dir($backUpDirectoryName)) 
    {
        DeleteDir($backUpDirectoryName);
    }
    mkdir($backUpDirectoryName, 0777, true);
    SetPerms($backUpDirectoryName);
    MoveAllFiles(getcwd(), getcwd()."/".$backUpDirectoryName, array("backup"), true);

    // download file
    $file_name = basename($url);
    file_put_contents($file_name, file_get_contents($url));

    //unzip file
    $saveToLink = getcwd();
    $zip = new ZipArchive;
    $res = $zip->open($file_name);
    if ($res === TRUE) 
    {
        $zip->extractTo($saveToLink);
        $zip->close();
    }
    else
    {
        return;
    }

    //so you can delete it manually
    SetPerms($unzippedFileName); 
    
    //move all extracted files to main folder
    MoveAllFiles($unzippedFileName, getcwd(), array("gitSync.php"));

    //delete after extracting
    DeleteDir($unzippedFileName);
    unlink($file_name);

    print("github synced");
?>