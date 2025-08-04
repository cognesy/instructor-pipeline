<?php declare(strict_types=1);

namespace Cognesy\Pipeline\Workflow;

use Closure;
use Cognesy\Pipeline\Contracts\CanProcessState;
use Cognesy\Pipeline\ProcessingState;

/**
 * Conditionally executes a pipeline based on processing state.
 *
 * The condition receives the current state and determines whether
 * the associated pipeline should execute. If not, the state passes
 * through unchanged.
 */
readonly class ConditionalStep implements CanProcessState
{
    /**
     * @param Closure(ProcessingState):bool $condition
     */
    public function __construct(
        private Closure $condition,
        private CanProcessState $step,
    ) {}

    public function process(ProcessingState $state): ProcessingState {
        return match(true) {
            ($this->condition)($state) => $this->step->process($state),
            default => $state,
        };
    }
}