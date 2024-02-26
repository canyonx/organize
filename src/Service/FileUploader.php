<?php

namespace App\Service;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileUploader
{
    private $targetDirectory;
    private $slugger;

    /**
     * Constructeur de FileUploader
     *
     * @param [images_directory] $targetDirectory défini dans la configuration du service, config/services.yaml
     * @param SluggerInterface $slugger
     */
    public function __construct(
        string $targetDirectory,
        SluggerInterface $slugger
    ) {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    /**
     * Upload d'une image
     *
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file): string
    {
        // Récupère le nom de l'image
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // Slug le nom de l'image, évite les problèmes de caratères
        $safeFilename = $this->slugger->slug($originalFilename);
        // Créer un nouveau nom d'image unique
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            // Déplace le fichier dans le dossier des images
            $file->move($this->targetDirectory, $fileName);
        } catch (FileException $e) {
            // Message d'erreur lors de l'upload
            return $e;
        }

        // Retourne le nom du fichier image
        return $fileName;
    }
}

/**
 * 
 * MODE D'EMPLOI FILE UPLOADER pour $entity
 *
 */

/**
 * Service.yaml
 */
//  parameters:
//     images_directory: '%kernel.project_dir%/public/images/'

// # Argument pour le constructeur de FileUploader
//     App\Service\FileUploader:
//         arguments:
//             $targetDirectory: '%images_directory%'


/**
 * Formulaire de entity
 */
//  ->add('imageFile', FileType::class, [
// 'label' => 'Image',
// // Champ non associé à une propriété de l'entité Post
// 'mapped' => false,
// // Champ optionnel
// 'required' => false,
// ])


/**
 * Lors de la création d'une entité
 */
// // Traitement de l'image
// // Type $imageFile comme un objet UploadedFile
// /** @var UploadedFile $imageFile */
// $imageFile = $form->get('imageFile')->getData();
// // Si une image est uploadé
// if ($imageFile) {
//     // Utilise Service/FileUploader pour enregistrer l'image
//     $imageFileName = $this->fileUploader->upload($imageFile);
//     // Met à jour la propriété image avec le nouveau nom de l'image
//     $entity->setImage($imageFileName);
// }


/**
 * Dans les fonctions de suppression d'une entité
 */
// if ($entity->getImage()) {
//     // Supprime l'image, chemin complet de l'image
//     $filesystem->remove($this->getParameter('images_directory') . '/' . $entity->getImage());
// }
// $this->em->remove($entity);


/**
 * Dans le traitement du formaulaire d'Upload
 */
//  // Traitement de l'image
// // Type $imageFile comme un objet UploadedFile
// /** @var UploadedFile $imageFile */
// $imageFile = $form->get('imageFile')->getData();
// // Si une image est uploadé
// if ($imageFile) {
//     // Supprime l'image actuelle, chemin complet de l'image
//     $this->filesystem->remove($this->getParameter('images_directory') . '/' . $entity->getImage());
//     // Utilise Service/FileUploader pour enregistrer l'image
//     $imageFileName = $this->fileUploader->upload($imageFile);
//     // Met à jour la propriété image avec le nouveau nom de l'image
//     $entity->setImage($imageFileName);
// }