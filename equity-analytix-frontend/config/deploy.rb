# config valid only for current version of Capistrano

set :application, 'equity-analytix'

# Default branch is :master
#ask :branch, "development"

# Default deploy_to directory is /var/www/my_app_name
# set :deploy_to, '/var/www/my_app_name'

# Default value for :scm is :git
# set :scm, :git

# Default value for :format is :airbrussh.
# set :format, :airbrussh

# You can configure the Airbrussh format using :format_options.
# These are the defaults.
# set :format_options, command_output: true, log_file: 'log/capistrano.log', color: :auto, truncate: :auto

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
# append :linked_files, 'config/database.yml', 'config/secrets.yml'

# Default value for linked_dirs is []
# append :linked_dirs, 'log', 'tmp/pids', 'tmp/cache', 'tmp/sockets', 'public/system'

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for keep_releases is 5
set :keep_releases, 3

namespace :deploy do
   task :rewrite_params do
        stage = fetch(:stage)
        on roles(:app) do
            run_locally do
                execute(:cp, "appConfig.#{stage}.js", 'appConfig.js')
            end
        end
    end
    task :build do
        on roles(:app) do
            run_locally do
                execute(:yarn, "")
                execute(:yarn, "build")
            end
        end
    end
    task :create_symlink_to_web do
        on roles(:app) do
            execute(:ln, "-sfn", "#{current_path}", fetch(:public_path))
        end
    end
end

before   "deploy:starting",         "deploy:build"
before   "deploy:build",            "deploy:rewrite_params"
after    "deploy:finished",         "deploy:create_symlink_to_web"
