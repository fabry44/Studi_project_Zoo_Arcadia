<?php

namespace App\Service;

class extractDashboardService
{
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
         * Extrait le tableau de bord à partir du chemin donné.
         *
         * Cette fonction prend en paramètre un chemin et recherche un motif correspondant à un tableau de bord.
         * Si un tableau de bord est trouvé, la fonction retourne le nom du tableau de bord correspondant.
         * Sinon, la fonction retourne null.
         *
         * @param string $path Le chemin à analyser.
         * @return string|null Le nom du tableau de bord extrait ou null si aucun tableau de bord n'est trouvé.
         */
        public function extractDashboardFromPath($path): ?string
        {
            $pattern = '/\/(admin|veterinaire|employe)-dashboard/';
            if (preg_match($pattern, $path, $matches)) {
                return $matches[1] . '_dashboard'; // retourne 'admin_dashboard', 'veterinaire_dashboard', etc.
            }

            return null;
        }
}