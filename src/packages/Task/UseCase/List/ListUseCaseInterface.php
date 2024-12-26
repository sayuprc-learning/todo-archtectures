<?php

declare(strict_types=1);

namespace Task\UseCase\List;

interface ListUseCaseInterface
{
    public function handle(ListRequest $request): ListResponse;
}
