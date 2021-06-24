# config valid only for current version of Capistrano

set :application, 'Equity-analytics'

# Default value for keep_releases is 5
set :keep_releases, 3

set :composer_install_flags, '--no-dev --no-interaction --optimize-autoloader'

namespace :deploy do

    task :rewrite_params do
        stage = fetch(:stage)
        on release_roles(:app) do
            within release_path do
                execute(:ln, "-srn", "config/wp-config.php.#{stage}", 'web/wp-config.php')
            end
        end
    end

    task :create_symlink_to_wp_content do
        on roles(:app) do
            #remove default wp-content
            execute(:rm, "-rf", "#{release_path}/web/wp-content")
            #create wp-content
            execute(:mkdir, "-p", "#{shared_path}/web/wp-content/plugins")
            #link shared wp-content
            execute(:ln, "-sfn", "#{shared_path}/web/wp-content", "#{release_path}/web/wp-content")
            #link equity_plugin into plugins directory
            execute(:ln, "-sfn", "#{release_path}/equity_plugin",  "#{shared_path}/web/wp-content/plugins/equity_plugin")
            #link equity_theme into theme directory
            execute(:ln, "-sfn", "#{release_path}/equity_theme",  "#{shared_path}/web/wp-content/themes/equity_theme")
        end
    end

    task :wp_db_update do
        on roles(:app) do
            execute("#{release_path}/vendor/bin/wp core update-db --path=#{release_path}/web")
        end
    end

    task :create_symlink_to_web do
        on roles(:app) do
            public_path = fetch(:public_path)
            #main web symlink
            execute(:ln, "-sfn", "#{current_path}/web", "#{public_path}")
        end
    end
end

after 'composer:run',                       'deploy:rewrite_params'
after 'deploy:rewrite_params',              'deploy:create_symlink_to_wp_content'
after 'deploy:create_symlink_to_wp_content','deploy:wp_db_update'
after 'deploy:finished',                    'deploy:create_symlink_to_web'
