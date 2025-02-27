Commands
====

To list all available tasks, please run:

.. code-block::

 docker-composer exec web ./vendor/bin/run

See bellow current list of available commands:

.. toolkit-block-commands

.. code-block::

 Available commands:
   completion                        Dump the shell completion script
   config                            Dumps the current or given configuration.
   help                              Display help for a command
   list                              List commands
  docker
   docker:refresh-configuration      [dk-rc] Update docker-compose.yml file based on project's configurations.
  drupal
   drupal:check-permissions          Command to check the forbidden permissions.
   drupal:config-import              Run the Drupal config import.
   drupal:disable-cache              Disable aggregation and clear cache.
   drupal:drush-setup                Write Drush configuration file at "${drupal.root}/drush/drush.yml".
   drupal:permissions-setup          Setup Drupal permissions.
   drupal:settings-setup             Setup Drupal settings.php file in compliance with Toolkit conventions.
   drupal:site-install               [drupal:si|dsi] Install target site.
   drupal:site-post-install          Run Drupal post-install commands.
   drupal:site-pre-install           Run Drupal pre-install commands.
   drupal:symlink-project            Symlink project as module, theme or profile in the proper directory.
   drupal:upgrade-status             [tdus] Check project compatibility for Drupal 9/10 upgrade.
  toolkit
   toolkit:build-assets              [tk-bassets|tk-assets|tba] Build theme assets (Css and Js).
   toolkit:build-dev                 [tk-bdev] Build site for local development.
   toolkit:build-dev-reset           [tk-bdev-reset] Build site for local development from scratch with a clean git.
   toolkit:build-dist                [tk-bdist] Build the distribution package.
   toolkit:check-phpcs-requirements  Make sure that the config file exists and configuration is correct.
   toolkit:check-version             Check the Toolkit version.
   toolkit:code-review               This command will execute all the testing tools.
   toolkit:complock-check            Check if 'composer.lock' exists on the project root folder.
   toolkit:component-check           Check composer for components that are not whitelisted/blacklisted.
   toolkit:create-dump               [tk-cdump] Export the local snapshot.
   toolkit:download-dump             [tk-ddump] Download ASDA snapshot.
   toolkit:fix-permissions           Run script to fix permissions (experimental).
   toolkit:hooks-delete-all          [tk-hdel] Remove all existing hooks, this will ignore active hooks list.
   toolkit:hooks-disable             [tk-hdis] Disable the git hooks.
   toolkit:hooks-enable              [tk-hen] Enable the git hooks defined in the configuration or in given option.
   toolkit:hooks-list                [tk-hlist] List available hooks and its status.
   toolkit:hooks-run                 [tk-hrun] Run a specific hook.
   toolkit:import-config             [DEPRECATED] Run the Drupal config import.
   toolkit:install-clean             [tk-iclean] Install a clean website.
   toolkit:install-clone             [tk-iclone] Install a clone website.
   toolkit:install-dependencies      Install packages present in the opts.yml file under extra_pkgs section.
   toolkit:install-dump              [tk-idump] Import the production snapshot.
   toolkit:lint-js                   [tk-js|tljs] Run lint JS.
   toolkit:lint-php                  [tk-php|tlp] Run lint PHP.
   toolkit:lint-yaml                 [tk-yaml|tly] Run lint YAML.
   toolkit:opts-review               Check project's .opts.yml file for forbidden commands.
   toolkit:patch-download            [tk-pd] Download remote patches into a local directory.
   toolkit:patch-list                [tk-pl] Download remote patches into a local directory.
   toolkit:requirements              Check the Toolkit Requirements.
   toolkit:run-blackfire             [tk-bfire|tbf] Run Blackfire.
   toolkit:run-deploy                Run deployment sequence.
   toolkit:run-gitleaks              [tk-gitleaks] Executes the Gitleaks.
   toolkit:run-phpcbf                [tk-phpcbf] Run PHP code autofixing.
   toolkit:setup-behat               Setup the Behat file.
   toolkit:setup-blackfire-behat     Copy the needed resources to run Behat with Blackfire.
   toolkit:setup-eslint              Setup the ESLint configurations and dependencies.
   toolkit:setup-phpcs               Setup PHP code sniffer.
   toolkit:setup-phpunit             Setup the PHPUnit file.
   toolkit:test-behat                [tk-behat|tb] Run Behat tests.
   toolkit:test-phpcs                [tk-phpcs] Run PHP code sniffer.
   toolkit:test-phpmd                [tk-phpmd] Run PHPMD.
   toolkit:test-phpstan              [tk-phpstan] Run PHPStan.
   toolkit:test-phpunit              [tk-phpunit|tp] Run PHPUnit tests.
   toolkit:vendor-list               Check 'Vendor' packages being monitored.

.. toolkit-block-commands-end

Creating custom commands
----

To provide custom commands, make sure that your classes are loaded, for example using
PSR-4 namespacing set the autoload in the composer.json file.

.. code-block::

    {
      "autoload": {
        "psr-4": {
          "My\\Project\\": "./src/"
        }
      }
    }

Create your command class under ``src/TaskRunner/Commands`` that will extend the abstract Toolkit command, like:

.. code-block::

    <?php
    namespace My\Project\TaskRunner\Commands;

    use EcEuropa\Toolkit\TaskRunner\AbstractCommands;

    class ExampleCommands extends AbstractCommands {
      /** @command example:first-command */
      public function commandOne() { }
    }

For more detail, check the `consolidation/annotated-command <https://github.com/consolidation/annotated-command#hooks>`_
documentation.

Passing default options for a command
----

You can pass default values for the command options, for that you
need to define a configuration file, and import it as shown below.

.. code-block::

    # config/commands/config.yml
    commands:
      example:
        first-command:
          options:
            output: false

.. code-block::

    <?php
    namespace My\Project\TaskRunner\Commands;

    use EcEuropa\Toolkit\TaskRunner\AbstractCommands;
    use Symfony\Component\Console\Input\InputOption;

    class ExampleCommands extends AbstractCommands {
      public function getConfigurationFile() {
        return __DIR__ . '/../../../config/commands/config.yml';
      }

      /**
       * @command example:first-command
       * @option output This is a test option
       */
      public function commandOne($options = [
        'output' => InputOption::VALUE_REQUIRED
      ]) { }
    }

Creating configuration commands
----

Configuration commands are created in the configuration file ``runner.yml``, like shown below:

.. code-block:: yaml

    commands:
      drupal:setup-test:
        - { task: process, source: behat.yml.dist, destination: behat.yml }

      drupal:setup-test2:
        aliases: test
        description: 'Setup the behat file'
        help: 'Some help text'
        hidden: false
        usage: '--simulate'
        tasks:
          - { task: process, source: behat.yml.dist, destination: behat.yml }

The configuration commands are a mapping to the `Robo Tasks <https://robo.li/#tasks>`_, the
list of available tasks is:

+---------------+------------------------------------------------------------------------+
| Task          | Robo Task                                                              |
+===============+========================================================================+
| mkdir         | `FilesystemStack <https://robo.li/tasks/Filesystem/#filesystemstack>`_ |
+---------------+------------------------------------------------------------------------+
| touch         | `FilesystemStack <https://robo.li/tasks/Filesystem/#filesystemstack>`_ |
+---------------+------------------------------------------------------------------------+
| copy          | `FilesystemStack <https://robo.li/tasks/Filesystem/#filesystemstack>`_ |
+---------------+------------------------------------------------------------------------+
| copyDir       | `FilesystemStack <https://robo.li/tasks/Filesystem/#filesystemstack>`_ |
+---------------+------------------------------------------------------------------------+
| chmod         | `FilesystemStack <https://robo.li/tasks/Filesystem/#filesystemstack>`_ |
+---------------+------------------------------------------------------------------------+
| chgrp         | `FilesystemStack <https://robo.li/tasks/Filesystem/#filesystemstack>`_ |
+---------------+------------------------------------------------------------------------+
| chown         | `FilesystemStack <https://robo.li/tasks/Filesystem/#filesystemstack>`_ |
+---------------+------------------------------------------------------------------------+
| remove        | `FilesystemStack <https://robo.li/tasks/Filesystem/#filesystemstack>`_ |
+---------------+------------------------------------------------------------------------+
| rename        | `FilesystemStack <https://robo.li/tasks/Filesystem/#filesystemstack>`_ |
+---------------+------------------------------------------------------------------------+
| symlink       | `FilesystemStack <https://robo.li/tasks/Filesystem/#filesystemstack>`_ |
+---------------+------------------------------------------------------------------------+
| mirror        | `FilesystemStack <https://robo.li/tasks/Filesystem/#filesystemstack>`_ |
+---------------+------------------------------------------------------------------------+
| process       | `Process </src/Task/File/Process.php>`_                                |
+---------------+------------------------------------------------------------------------+
| append        | `Write with append() <https://robo.li/tasks/File/#write>`_             |
+---------------+------------------------------------------------------------------------+
| run           | Executes a Runner task                                                 |
+---------------+------------------------------------------------------------------------+
| exec          | `Exec <https://robo.li/tasks/Base/#exec>`_                             |
+---------------+------------------------------------------------------------------------+
| drush         | Executes a Drush command                                               |
+---------------+------------------------------------------------------------------------+
| replace-block | `ReplaceBlock </src/Task/File/ReplaceBlock.php>`_                      |
+---------------+------------------------------------------------------------------------+
