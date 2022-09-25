<?php

namespace App\Services;

use App\Repositories\ReportRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;

class ReportService extends BaseService
{
    protected ReportRepository $reportRepository;
    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function getAll($request)
    {
        return $this->reportRepository->getAll($request);
    }
}
