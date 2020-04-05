<?php


namespace Drupal\graphql_people\Plugin\GraphQL\SchemaExtension;


use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\graphql\GraphQL\ResolverBuilder;
use Drupal\graphql\GraphQL\ResolverRegistryInterface;
use Drupal\nyx_graphql\Plugin\GraphQL\Schema\EntityExtension;

/**
 * @SchemaExtension(
 *   id = "user_extension",
 *   name = "Users avgd's page extension",
 *   description = "A extension that adds pages related fields.",
 *   schema = "entity"
 * )
 */
class UserExtension extends EntityExtension {

  /**
   * @inheritDoc
   */
  public function __construct($configuration, $pluginId, $pluginDefinition, ModuleHandlerInterface $moduleHandler) {
    parent::__construct($configuration, $pluginId, $pluginDefinition, $moduleHandler);
    $this->entity = [
      'type' => 'user',
      'bundle' => 'user',
      'plural' => 'users',
      'entity' => 'entity:user'
    ];
  }

  public function addFields(ResolverRegistryInterface $registry, ResolverBuilder $builder) {
    parent::addFields($registry, $builder);

    $registry->addFieldResolver('User', 'mail',
      $builder->produce('user_email')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver('User', 'username',
      $builder->produce('entity_label')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver('User', 'status',
      $builder->produce('user_status')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver('User', 'roles',
      $builder->produce('user_roles')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver('Mutation', 'createPeople',
      $builder->produce('create_user')
        ->map('data', $builder->fromArgument('data'))
    );

    $registry->addFieldResolver('Mutation', 'updatePeople',
      $builder->produce('update_user')
        ->map('data', $builder->fromArgument('data'))
    );

    $registry->addFieldResolver('Mutation', 'deletePeople',
      $builder->produce('delete_user')
        ->map('data', $builder->fromArgument('data'))
    );
  }

  function addQueryFields(ResolverRegistryInterface $registry, ResolverBuilder $builder) {
    parent::addQueryFields($registry, $builder); // TODO: Change the autogenerated stub

    $registry->addFieldResolver('Query', $this->entity['type'],
      $builder->produce('user_load')
        ->map('type', $builder->fromValue($this->entity['type']))
        ->map('bundles', $builder->fromValue([$this->entity['bundle']]))
        ->map('id', $builder->fromArgument('id'))
    );

    $registry->addFieldResolver('Query', $this->entity['plural'],
      $builder->produce('query_entities')
        ->map('type', $builder->fromValue($this->entity['type']))
        ->map('offset', $builder->fromArgument('offset'))
        ->map('limit', $builder->fromArgument('limit'))
        ->map('filters', $builder->fromArgument('filters'))
    );
  }

}
