<?php

namespace App\Serializer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class DoctrineDenormalizer implements DenormalizerInterface
{
	private $entityManagerInterface;

	public function __construct(EntityManagerInterface $entityManagerInterface)
	{
		$this->entityManagerInterface = $entityManagerInterface;
	}

	/**
	 * Do i know how to do this denormalization ?
	 *
	 * @param mixed $data Entity Id
	 * @param string $type Class entity type (FQCN)
	 * @param string|null $format 
	 * @return void
	 */
	public function supportsDenormalization($data, string $type, ?string $format = null): bool
	{
		// example for Movies::Genres
		// First, i check if the FQCN begin per "App\Entity"
		$isEntity = strpos($type, 'App\Entity') === 0;

		// And, i check if $data is numeric (ID)
		$isIdentifiant = is_numeric($data);

		return $isEntity && $isIdentifiant;
	}

	public function denormalize($data, string $type, ?string $format = null, array $context = []): mixed
	{
		$entity = $this->entityManagerInterface->find($type, $data);

		return $entity;
	}
}
