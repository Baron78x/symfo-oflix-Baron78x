<?php

namespace App\Command;

use App\Repository\MovieRepository;
use App\Service\OmdbApi;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MoviesGetposterCommand extends Command
{
    protected static $defaultName = 'app:movies:getposter';
    protected static $defaultDescription = 'Update movie poster from OMDBAPI';

    private $movieRepository;
    private $doctrine;
    private $omdbApi;

    /**
     * On fait appel à tous les services nécessaires au fonctionnement de la commande
     */
    public function __construct(
        MovieRepository $movieRepository,
        ManagerRegistry $doctrine,
        OmdbApi $omdbApi
    ) {
        $this->movieRepository = $movieRepository;
        $this->doctrine = $doctrine;
        $this->omdbApi = $omdbApi;

        // On doit appeler le constructeur du parent depius notre propre constructeur
        // @link https://symfony.com/doc/current/console.html#configuring-the-command
        parent::__construct();
    }
    
    protected function configure(): void
    {
        $this
             ->addArgument('title', InputArgument::OPTIONAL, 'Movie title to get')
        //     ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $movieTitle = $input->getArgument('title');
        // dump($movieTitle);

        // if ($arg1) {
        //     $io->note(sprintf('You passed an argument: %s', $arg1));
        // }

        // Pour afficher plus de chsoes à l'écran
        $verbose = $input->getOption('verbose');

        $io->info('Updating posters...');

        if ($movieTitle !== null) {

            $movie = $this->movieRepository->findOneByTitle($movieTitle);

            // Film non trouvé ?
            if ($movie === null) {
                $io->error('Film non trouvé');

                return COMMAND::INVALID;
            }

            // On ajoute le film au tableau parcouru ci-dessous
            $movies = [$movie];

        } else {
            // Récupérer tous les films (via MovieRepository + findAll())
            $movies = $this->movieRepository->findAll();
        }

        // En sortie de cette condition, on a au moins 1 film dans la liste

        // Pour chaque film
        foreach ($movies as $movie) {
            // On veut accéder à l'API pour récupérer les infos de ce film
            // et dans ce cas précis, le poster
            $poster = $this->omdbApi->fetchPoster($movie->getTitle());

            // Si "verbose"
            if ($verbose) {
                $io->note($movie->getTitle());
            }

            // On met à jour le poster
            $movie->setPoster($poster);
        }

        // On flush (on UPDATE les films en base) via MovieRepository ou Doctrine Manager
        // Pas de persist() ! les objets sont déjà "persistés" en base
        $this->doctrine->getManager()->flush();

        $io->success('Posters updated !');

        return Command::SUCCESS;
    }
}
