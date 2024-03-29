<?php

/**
 * This file is part of the RJMCommentBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RJM\CommentBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Configures the DI container for CommentBundle.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class RJMCommentExtension extends Extension
{
    /**
     * Loads and processes configuration to configure the Container.
     *
     * @throws InvalidArgumentException
     * @param  array                    $configs
     * @param  ContainerBuilder         $container
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->process($configuration->getConfigTree(), $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        if (!in_array(strtolower($config['db_driver']), array('mongodb', 'orm'))) {
            throw new \InvalidArgumentException(sprintf('Invalid db driver "%s".', $config['db_driver']));
        }
        $loader->load(sprintf('%s.xml', $config['db_driver']));

        foreach (array('events', 'form', 'twig', 'sorting') as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        // only load acl services if acl is enabled for the project
        if (array_key_exists('acl', $config)) {
            $this->loadAcl($container, $config);
        }

        $container->setParameter('rjm_comment.template.engine', $config['template']['engine']);

        $container->setParameter('rjm_comment.model.comment.class', $config['class']['model']['comment']);
        $container->setParameter('rjm_comment.model.thread.class', $config['class']['model']['thread']);

        if (array_key_exists('vote', $config['class']['model'])) {
            $container->setParameter('rjm_comment.model.vote.class', $config['class']['model']['vote']);
        }

        $container->setParameter('rjm_comment.model_manager_name', $config['model_manager_name']);

        // handle the MongoDB document manager name in a specific way as it does not have a registry to make it easy
        // TODO: change it if https://github.com/symfony/DoctrineMongoDBBundle/pull/31 is merged
        if ('mongodb' === $config['db_driver']) {
            if (null === $config['model_manager_name']) {
                $container->setAlias('rjm_comment.document_manager', new Alias('doctrine.odm.mongodb.document_manager', false));
            } else {
                $container->setAlias('rjm_comment.document_manager', new Alias(sprintf('doctrine.odm.%s_mongodb.document_manager', $config['model_manager_name']), false));
            }
        }

        $container->setParameter('rjm_comment.form.comment.type', $config['form']['comment']['type']);
        $container->setParameter('rjm_comment.form.comment.name', $config['form']['comment']['name']);

        $container->setParameter('rjm_comment.form.thread.type', $config['form']['thread']['type']);
        $container->setParameter('rjm_comment.form.thread.name', $config['form']['thread']['name']);

        $container->setParameter('rjm_comment.form.commentable_thread.type', $config['form']['commentable_thread']['type']);
        $container->setParameter('rjm_comment.form.commentable_thread.name', $config['form']['commentable_thread']['name']);

        $container->setParameter('rjm_comment.form.delete_comment.type', $config['form']['delete_comment']['type']);
        $container->setParameter('rjm_comment.form.delete_comment.name', $config['form']['delete_comment']['name']);

        $container->setParameter('rjm_comment.form.vote.type', $config['form']['vote']['type']);
        $container->setParameter('rjm_comment.form.vote.name', $config['form']['vote']['name']);

        $container->setParameter('rjm_comment.sorting_factory.default_sorter', $config['service']['sorting']['default']);

        $container->setAlias('rjm_comment.form_factory.comment', $config['service']['form_factory']['comment']);
        $container->setAlias('rjm_comment.form_factory.commentable_thread', $config['service']['form_factory']['commentable_thread']);
        $container->setAlias('rjm_comment.form_factory.delete_comment', $config['service']['form_factory']['delete_comment']);
        $container->setAlias('rjm_comment.form_factory.thread', $config['service']['form_factory']['thread']);
        $container->setAlias('rjm_comment.form_factory.vote', $config['service']['form_factory']['vote']);

        if (isset($config['service']['spam_detection'])) {
            $loader->load('spam_detection.xml');
            $container->setAlias('rjm_comment.spam_detection.comment', $config['service']['spam_detection']['comment']);
        }

        if (isset($config['service']['markup'])) {
            $container->setAlias('rjm_comment.markup', new Alias($config['service']['markup'], false));
            $loader->load('markup.xml');
        }

        $container->setAlias('rjm_comment.manager.thread', $config['service']['manager']['thread']);
        $container->setAlias('rjm_comment.manager.comment', $config['service']['manager']['comment']);
        $container->setAlias('rjm_comment.manager.vote', $config['service']['manager']['vote']);
    }

    protected function loadAcl(ContainerBuilder $container, array $config)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('acl.xml');

        foreach (array(1 => 'create', 'view', 'edit', 'delete') as $index => $perm) {
            $container->getDefinition('rjm_comment.acl.comment.roles')->replaceArgument($index, $config['acl_roles']['comment'][$perm]);
            $container->getDefinition('rjm_comment.acl.thread.roles')->replaceArgument($index, $config['acl_roles']['thread'][$perm]);
            $container->getDefinition('rjm_comment.acl.vote.roles')->replaceArgument($index, $config['acl_roles']['vote'][$perm]);
        }

        $container->setAlias('rjm_comment.acl.thread', $config['service']['acl']['thread']);
        $container->setAlias('rjm_comment.acl.comment', $config['service']['acl']['comment']);
        $container->setAlias('rjm_comment.acl.vote', $config['service']['acl']['vote']);
    }
}
