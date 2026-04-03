<?php

declare(strict_types=1);

namespace Shubo\CompanyAccount\Api\Data;

/**
 * Company team data interface.
 *
 * @api
 */
interface CompanyTeamInterface
{
    public const TEAM_ID = 'team_id';
    public const COMPANY_ID = 'company_id';
    public const NAME = 'name';
    public const DESCRIPTION = 'description';
    public const PARENT_TEAM_ID = 'parent_team_id';

    /**
     * Get team ID.
     *
     * @return int|null
     */
    public function getTeamId(): ?int;

    /**
     * Set team ID.
     *
     * @param int $teamId
     * @return self
     */
    public function setTeamId(int $teamId): self;

    /**
     * Get company ID.
     *
     * @return int
     */
    public function getCompanyId(): int;

    /**
     * Set company ID.
     *
     * @param int $companyId
     * @return self
     */
    public function setCompanyId(int $companyId): self;

    /**
     * Get team name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set team name.
     *
     * @param string $name
     * @return self
     */
    public function setName(string $name): self;

    /**
     * Get team description.
     *
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * Set team description.
     *
     * @param string|null $description
     * @return self
     */
    public function setDescription(?string $description): self;

    /**
     * Get parent team ID.
     *
     * @return int|null
     */
    public function getParentTeamId(): ?int;

    /**
     * Set parent team ID.
     *
     * @param int|null $parentTeamId
     * @return self
     */
    public function setParentTeamId(?int $parentTeamId): self;
}
