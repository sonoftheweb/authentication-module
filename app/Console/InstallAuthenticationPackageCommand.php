<?php

namespace Modules\Authentication\Console;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InstallAuthenticationPackageCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'auth:install-package';

    /**
     * The console command description.
     */
    protected $description = 'Install authentication package (Laravel Passport or Sanctum)';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $package = $this->choice(
            'Which authentication package would you like to install?',
            [
                'passport' => 'Laravel Passport - Full OAuth2 server implementation',
                'sanctum' => 'Laravel Sanctum - Lightweight authentication for SPAs and mobile applications'
            ],
            'sanctum'
        );

        $this->info("Installing {$package}...");

        if ($package === 'passport') {
            return $this->installPassport();
        }

        return $this->installSanctum();
    }

    /**
     * Get the console command arguments.
     */
    protected function getArguments(): array
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }

    private function installPassport(): int
    {
        $this->info('Installing Laravel Passport...');

        $this->call('install:api', ['--passport' => true]);

        $this->updateUserModel('passport');

        $this->info('Laravel Passport has been installed successfully!');
        return Command::SUCCESS;
    }

    private function installSanctum(): int
    {
        $this->info('Installing Laravel Sanctum...');

        $this->call('install:api');

        $this->updateUserModel('sanctum');

        $this->info('Laravel Sanctum has been installed successfully!');
        return Command::SUCCESS;
    }

    private function updateUserModel(string $package): void
    {
        $userModelPath = module_path('Authentication', 'app/Models/User.php');
        $this->info("Updating User model with {$package} traits...");

        $content = match($package) {
            'passport' => <<<'PHP'
<?php

namespace Modules\Authentication\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\User as BaseUser;

class User extends BaseUser implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
PHP,
            'sanctum' => <<<'PHP'
<?php

namespace Modules\Authentication\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\User as BaseUser;

class User extends BaseUser implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
PHP,
        };

        file_put_contents($userModelPath, $content);
        $this->info('User model updated successfully.');
    }
}
