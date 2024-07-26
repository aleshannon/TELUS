<?php

use App\Entity\Summary;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\Request;

// Load the Dotenv component to handle environment variables
require dirname(__DIR__).'/vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(dirname(__DIR__).'/.env');

// Boot the Symfony kernel
$kernel = new App\Kernel('dev', true);
$request = Request::createFromGlobals();
$kernel->boot();

$container = $kernel->getContainer();
$entityManager = $container->get('doctrine')->getManager();

// Create a new Summary entity and set its properties
$summary = new Summary();
$summary->setTotalUsers(100);
$summary->setDate(new \DateTime());

// Persist the entity to the database
$entityManager->persist($summary);
$entityManager->flush();
