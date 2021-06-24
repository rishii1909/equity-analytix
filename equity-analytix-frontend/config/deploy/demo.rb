server '10.1.1.57',
  user: 'jenkins',
  roles: %w{web app},
  ssh_options: {
    forward_agent: false,
    auth_methods: %w(publickey password)
  }

set :rsh, 'ssh -o PasswordAuthentication=no -o StrictHostKeyChecking=no'
set :rsync_options, {
    source: './dist',
    cache: 'cached-copy',
    args: {
      local_to_remote: %W(--rsh #{fetch(:rsh)} -v --compress --recursive --delete --exclude-from=.rsync.exclude --delete-excluded),
      cache_to_release: %w(--archive)
    }
}

set :deploy_to, '/var/www/html/equity_chat/demo'
set :public_path, '/var/www/html/equity_chat/demo/web'

set :branch, "master"

set :file_permissions_users, ["jenkins"]
set :file_permissions_groups, ["apache"]
