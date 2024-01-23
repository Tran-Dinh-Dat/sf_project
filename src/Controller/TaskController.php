<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\Type\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('task/create', name: 'task_create')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();

        // $form = $this->createFormBuilder($task)
        //     ->add('task', TextType::class)
        //     ->add('dueDate', DateType::class)
        //     ->add('save', SubmitType::class, ['label' => 'Create task'])
        //     ->getForm();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();
            $entityManager->persist($task);
            $entityManager->flush();
            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('task_create');
        }

        return $this->render('task/new.html.twig', [
            'form' => $form,
        ]);
    }
}