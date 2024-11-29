<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

class UploaderQuackPicture {
    public function __construct(
        private Filesystem $fs,
        private $quackFolderPublic,
        private $quackFolder
    )
    {

    }
    public function uploadQuackImage($picture) {
        // dossier ou sont stocker les images ( service.yaml )
        $folder = $this->quackFolder;
        // extension de l'image
        $ext = $picture->guessExtension() ?? 'bin';
        // nom de l'image
        $filename = bin2hex(random_bytes(10)) . '.' . $ext;
        // place l'image dans profileFolder avec le nom du fichier généré
        $picture->move($this->quackFolder, $filename);
        // si ancienne image

        // le chemin du nouveau fichier
        return $this->quackFolderPublic . '/' . $filename;
    }
}
