<?php

namespace App\Services\AdminSeller;

use App\Models\User;
use App\Services\AdminSeller\Statistics\AudienceAnalyticsService;
use App\Services\AdminSeller\Statistics\DateRangeResolver;
use App\Services\AdminSeller\Statistics\PerformanceRankingService;
use App\Services\AdminSeller\Statistics\ScorecardMetricService;
use App\Services\AdminSeller\Statistics\SmartInsightEngine;
use App\Services\AdminSeller\Statistics\TimelineChartService;
use Carbon\Carbon;

class StatisticService
{
    public function __construct(
        protected ?DateRangeResolver $dateRangeResolver = null,
        protected ?ScorecardMetricService $scorecardMetricService = null,
        protected ?TimelineChartService $timelineChartService = null,
        protected ?AudienceAnalyticsService $audienceAnalyticsService = null,
        protected ?PerformanceRankingService $performanceRankingService = null,
        protected ?SmartInsightEngine $smartInsightEngine = null,
    ) {
        $this->dateRangeResolver = $dateRangeResolver ?? new DateRangeResolver;
        $this->scorecardMetricService = $scorecardMetricService ?? new ScorecardMetricService;
        $this->timelineChartService = $timelineChartService ?? new TimelineChartService;
        $this->audienceAnalyticsService = $audienceAnalyticsService ?? new AudienceAnalyticsService;
        $this->performanceRankingService = $performanceRankingService ?? new PerformanceRankingService;
        $this->smartInsightEngine = $smartInsightEngine ?? new SmartInsightEngine;
    }

    /**
     * Resolve date range and previous comparison period based on preset or custom input.
     */
    public function resolveDateRange(?string $preset = '30days', ?string $startDate = null, ?string $endDate = null): array
    {
        return $this->dateRangeResolver->resolveDateRange($preset, $startDate, $endDate);
    }

    /**
     * Get comprehensive analytics data for the admin seller.
     */
    public function getAnalyticsData(User $user, ?string $preset = '30days', ?string $startDate = null, ?string $endDate = null): array
    {
        [$currentStart, $currentEnd, $prevStart, $prevEnd, $resolvedPreset] = $this->resolveDateRange($preset, $startDate, $endDate);

        $scorecards = $this->getScorecards($user, $currentStart, $currentEnd, $prevStart, $prevEnd);
        $timeline = $this->getTimelineChartData($user, $currentStart, $currentEnd);
        $transactionStatuses = $this->getTransactionStatusData($user, $currentStart, $currentEnd);
        $shortlinksPerf = $this->getShortlinksPerformance($user, $currentStart, $currentEnd);
        $productsPerf = $this->getDigitalProductsPerformance($user, $currentStart, $currentEnd);
        $audience = $this->getAudienceBreakdown($user, $currentStart, $currentEnd);
        $recentTransactions = $this->getRecentTransactions($user, 10);
        $insights = $this->generateSmartInsights($user, $scorecards, $audience, $productsPerf, $shortlinksPerf);
        $topItems = $this->buildTopPerformers($shortlinksPerf, $productsPerf);
        $rankedPages = $this->buildRankedPages($productsPerf, $shortlinksPerf);

        return [
            'status' => 'success',
            'preset' => $resolvedPreset,
            'start_date' => $currentStart->toDateString(),
            'end_date' => $currentEnd->toDateString(),
            'prev_start_date' => $prevStart->toDateString(),
            'prev_end_date' => $prevEnd->toDateString(),
            'scorecards' => $scorecards,
            'timeline' => $timeline,
            'labels' => $timeline['labels'],
            'views' => $timeline['views'],
            'clicks' => $timeline['clicks'],
            'sales' => $timeline['sales'],
            'transaction_statuses' => $transactionStatuses,
            'shortlinks' => $shortlinksPerf,
            'products' => $productsPerf,
            'audience' => $audience,
            'recent_transactions' => $recentTransactions,
            'insights' => $insights,
            'top_items' => $topItems,
            'ranked_pages' => $rankedPages,
        ];
    }

    /**
     * Backward-compatibility wrapper for legacy overview.
     */
    public function getStatisticOverview(User $user): array
    {
        return $this->getAnalyticsData($user, '30days');
    }

    /**
     * Compute 8 Hero Scorecards with percentage change vs previous period.
     */
    public function getScorecards(User $user, Carbon $start, Carbon $end, Carbon $prevStart, Carbon $prevEnd): array
    {
        return $this->scorecardMetricService->getScorecards($user, $start, $end, $prevStart, $prevEnd);
    }

    /**
     * Daily timeline series for traffic & revenue.
     */
    public function getTimelineChartData(User $user, Carbon $start, Carbon $end): array
    {
        return $this->timelineChartService->getTimelineChartData($user, $start, $end);
    }

    /**
     * Transaction status breakdown and timeline health.
     */
    public function getTransactionStatusData(User $user, Carbon $start, Carbon $end): array
    {
        return $this->timelineChartService->getTransactionStatusData($user, $start, $end);
    }

    /**
     * Shortlink performance table and charts data.
     */
    public function getShortlinksPerformance(User $user, Carbon $start, Carbon $end): array
    {
        return $this->performanceRankingService->getShortlinksPerformance($user, $start, $end);
    }

    /**
     * Digital Products performance table & charts.
     */
    public function getDigitalProductsPerformance(User $user, Carbon $start, Carbon $end): array
    {
        return $this->performanceRankingService->getDigitalProductsPerformance($user, $start, $end);
    }

    /**
     * Audience breakdown (Device, Geo Cities & Countries, Traffic Sources, Peak Hours & Days).
     */
    public function getAudienceBreakdown(User $user, Carbon $start, Carbon $end): array
    {
        return $this->audienceAnalyticsService->getAudienceBreakdown($user, $start, $end);
    }

    /**
     * Get recent transactions overview for quick preview.
     */
    public function getRecentTransactions(User $user, int $limit = 10): array
    {
        return $this->timelineChartService->getRecentTransactions($user, $limit);
    }

    /**
     * Generate dynamic Smart Highlights (Insight Cerdas) based on server-side rules.
     */
    public function generateSmartInsights(User $user, array $scorecards, array $audience, array $products, array $shortlinks): array
    {
        return $this->smartInsightEngine->generateSmartInsights($user, $scorecards, $audience, $products, $shortlinks);
    }

    /**
     * Build combined top performers list for overview tab.
     */
    public function buildTopPerformers(array $shortlinksPerf, array $productsPerf): array
    {
        return $this->performanceRankingService->buildTopPerformers($shortlinksPerf, $productsPerf);
    }

    /**
     * Build ranked pages/links for overview tab.
     */
    public function buildRankedPages(array $productsPerf, array $shortlinksPerf): array
    {
        return $this->performanceRankingService->buildRankedPages($productsPerf, $shortlinksPerf);
    }
}
