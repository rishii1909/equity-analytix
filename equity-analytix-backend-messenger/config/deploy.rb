set :stage,       fetch(:stage)
set :symfony_env, 'prod'

# Application name
set :application, 'Equity-analytics'

# Deploy configuration
set :symfony_directory_structure, 3
set :sensio_distribution_version, 5
set :app_path,                    nil
set :app_config_path,             'config'
set :web_path,                    'public'
set :var_path,                    'var'
set :bin_path,                    'bin'
set :log_path,                    "#{fetch :var_path}/log"
set :cache_path,                  "#{fetch :var_path}/cache"

set :symfony_console_path,  "#{fetch :bin_path}/console"
set :symfony_console_flags, '--no-debug'

# GIT config
set :repo_url, 'git@git.sibers.com:sibers/equity-analytix-backend-messenger.git'

set :linked_files, []
set :linked_dirs,  [ fetch(:var_path) ]

set :keep_releases, 3

set :permission_method,       :acl
set :file_permissions_users,  []
set :file_permissions_groups, []
set :file_permissions_paths,  [ fetch(:var_path) ]

# Composer
set :composer_install_flags, '--no-interaction --optimize-autoloader'

namespace :deploy do
    task :rewrite_params do
        stage = fetch(:stage)
        on release_roles(:app) do
            within release_path do
                execute(:cp, ".env.#{stage}", '.env.local')
            end
        end
    end

    task :create_symlink_to_web do
        on release_roles(:app) do
            execute(:ln, "-sfn", "#{current_path}/public", "#{deploy_to}/#{fetch:web_path}")
        end
    end

    task :migrate do
        on release_roles(:app) do
            invoke "symfony:console", "doctrine:migrations:migrate", "--no-interaction"
        end
    end
    task :load_fixtures do
        on roles(:app) do
            invoke "symfony:console", "doctrine:fixtures:load", "--env=dev", "--no-interaction"
        end
    end
end

before 'composer:run',                 'deploy:rewrite_params'
after  'composer:run',                 'symfony:set_permissions'
before 'deploy:cleanup',               'deploy:create_symlink_to_web'
after  'symfony:cache:warmup',         'deploy:migrate'
after  'deploy:migrate',               'deploy:load_fixtures'
