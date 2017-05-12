<?php

namespace App\Exporters;

use Maatwebsite\Excel\Facades\Excel;

class ResourceExporter
{

    public $resources;
    public $exportFileName;

    /**
     * ResourceExporter constructor.
     * @param $resources
     * @param $fileName
     */
    public function __construct($resources, $fileName)
    {
        $this->resources = $resources;
        $this->exportFileName = $fileName;
    }

    /**
     * Generate excel export
     * @param $type
     */
    public function generateExcelExport($type)
    {
        switch ($type) {
            case 'users':
                return static::generateUsersExport();
                break;
        }
    }

    /**
     * Generate users export
     * @return mixed
     */
    public function generateUsersExport()
    {
        return Excel::create($this->exportFileName, function($excel) {
            $resources = $this->resources;
            $exportArr = [];

            if ( count($resources) ) {
                foreach ($resources as $user) {
                    $exportArr[] = [
                        'First Name' => $user->first_name,
                        'Last Name' => $user->last_name,
                        'Email' => $user->email,
                        'Active' => $user->active ? '✔' : '✗',
                        'Super Admin' => $user->is_super_admin ? '✔' : '✗',
                    ];

                }
            }

            $excel->sheet('Users', function($sheet) use ($exportArr) {
                $sheet->fromArray($exportArr);
            });

        })->download('xls');
    }
}