<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        dd($products);

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/product/create', name: 'create_product')]
    public function createProduct(EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $product->setName('Keyboard');
        $product->setPrice(199999);
        $product->setDescription('Description the product!!!');
        $entityManager->persist($product);
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
    }

    #[Route('/product/{id}', name: 'show_product')]
    public function show(EntityManagerInterface $entityManager, int $id): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        return new Response('Check out this greate product: ' . $product->getName());
    }

    #[Route('/product2/{id}', name: 'show2_product')]
    public function show2(ProductRepository $productRepository, int $id): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        return new Response('Check out this greate product: ' . $product->getName());
    }

    #[Route('/product3/{id}', name: 'show3_product')]
    public function show3(Product $product): Response
    {
        return new Response('Check out this greate product: ' . $product->getName());
    }

    #[Route('/product/edit/{id}', name: 'update_product')]
    public function update(EntityManagerInterface $entityManager, int $id): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $product->setName('Keyboard updated');
        $entityManager->flush();

        return $this->redirectToRoute('show_product', [
            'id' => $product->getId()
        ]);
    }

    #[Route('/product/delete/{id}', name: 'delete_product')]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return new Response('Deleted product ' . $product->getName());
    }

    #[Route('/product-find', name: 'find_product')]
    public function findAllGreaterThanPrice(EntityManagerInterface $entityManager): Response
    {
        $minPrice = 1000;

        $products = $entityManager->getRepository(Product::class)->findAllGreaterThanPrice($minPrice);
        dd($products);
    }
}
