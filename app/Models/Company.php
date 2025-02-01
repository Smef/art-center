<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;

class Company extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::deleting(function (Company $company) {
            // delete the logo before deleting the company model from the database
            $company->deleteCompanyFiles();
        });
    }

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'logo_url',
    ];

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    protected function logoUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (empty($this->logo_filename)) {
                    return null;
                }

                $path = Storage::url($this->getLogoStorageDirectory().$this->logo_filename);

                return $path;
            },
        );
    }

    protected function getStorageRootDir(): string
    {
        return '/companies/'.$this->id.'/';
    }

    protected function getLogoStorageDirectory(): string
    {
        return $this->getStorageRootDir().'logo/';
    }

    public function deleteLogo()
    {
        $storagePath = $this->getLogoStorageDirectory();
        $result = Storage::deleteDirectory($storagePath);

        if (! $result) {
            throw new \Exception('Failed to delete logo');
        }

        $this->logo_filename = null;
        $this->save();
    }

    public function storeLogo(File $file)
    {
        $storageDirectory = $this->getLogoStorageDirectory();

        // delete any existing file
        Storage::deleteDirectory($storageDirectory);

        // upload the new file as logo.ext
        $fileName = 'logo.'.$file->getClientOriginalExtension();
        $path = Storage::putFileAs($storageDirectory, $file, $fileName);

        // record the filename in the database
        $this->logo_filename = $fileName;

        return $path;
    }

    public function deleteCompanyFiles()
    {
        $storagePath = $this->getStorageRootDir();
        Storage::deleteDirectory($storagePath);
    }
}
