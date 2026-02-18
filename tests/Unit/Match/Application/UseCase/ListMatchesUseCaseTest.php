<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Application\UseCase;

use App\Match\Application\Query\ListMatchesQuery;
use App\Match\Application\UseCase\ListMatchesUseCase;
use App\SharedContext\Application\Bus\QueryBusInterface;
use App\SharedContext\Domain\Pagination\PaginatedResult;
use PHPUnit\Framework\TestCase;

final class ListMatchesUseCaseTest extends TestCase
{
    private QueryBusInterface $queryBus;
    private ListMatchesUseCase $useCase;

    protected function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBusInterface::class);
        $this->useCase = new ListMatchesUseCase($this->queryBus);
    }

    public function testExecuteReturnsPaginatedResult(): void
    {
        $paginatedResult = new PaginatedResult(
            items: [],
            total: 2,
            page: 1,
            limit: 20,
        );

        $this->queryBus
            ->expects($this->once())
            ->method('handle')
            ->with($this->isInstanceOf(ListMatchesQuery::class))
            ->willReturn($paginatedResult);

        $result = $this->useCase->execute();

        $this->assertInstanceOf(PaginatedResult::class, $result);
        $this->assertSame(2, $result->total);
    }

    public function testExecuteReturnsEmptyPaginatedResult(): void
    {
        $paginatedResult = new PaginatedResult(
            items: [],
            total: 0,
            page: 1,
            limit: 20,
        );

        $this->queryBus
            ->expects($this->once())
            ->method('handle')
            ->with($this->isInstanceOf(ListMatchesQuery::class))
            ->willReturn($paginatedResult);

        $result = $this->useCase->execute();

        $this->assertInstanceOf(PaginatedResult::class, $result);
        $this->assertEmpty($result->items);
        $this->assertSame(0, $result->total);
    }

    public function testExecuteDispatchesCorrectQuery(): void
    {
        $paginatedResult = new PaginatedResult(
            items: [],
            total: 0,
            page: 1,
            limit: 20,
        );

        $this->queryBus
            ->expects($this->once())
            ->method('handle')
            ->with($this->callback(function ($query) {
                return $query instanceof ListMatchesQuery;
            }))
            ->willReturn($paginatedResult);

        $this->useCase->execute();
    }

    public function testExecutePassesPaginationParams(): void
    {
        $paginatedResult = new PaginatedResult(
            items: [],
            total: 0,
            page: 3,
            limit: 10,
        );

        $this->queryBus
            ->expects($this->once())
            ->method('handle')
            ->with($this->callback(function ($query) {
                return $query instanceof ListMatchesQuery
                    && 3 === $query->pagination->page
                    && 10 === $query->pagination->limit;
            }))
            ->willReturn($paginatedResult);

        $this->useCase->execute(3, 10);
    }
}
