<?php
namespace App\Tests\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use App\Domain\Entity\ValueObject\Id;
use Ramsey\Uuid\Uuid;

class Api extends \Codeception\Module
{
    use AuthenticationTrait;
    use ContainerTrait;

    /**
     * @return \App\Domain\Entity\ValueObject\Id
     * @throws \Exception
     */
    public function generateId(): Id
    {
        $uuid = Uuid::uuid4();

        return new Id($uuid->toString());
    }

    public function getRootResponseWithItemsJsonType(): array
    {
        return [
            'data' => [
                'items' => 'array',
            ],
        ];
    }

    public function getTransactionDtoJsonType(): array
    {
        return [
            'id' => 'string',
            'author' => $this->getUserDtoJsonType(),
            'type' => 'string',
            'accountId' => 'string',
            'accountRecipientId' => 'string|null',
            'amount' => 'float|integer',
            'amountRecipient' => 'float|integer|null',
            'categoryId' => 'string|null',
            'description' => 'string',
            'payeeId' => 'string|null',
            'tagId' => 'string|null',
            'date' => 'string',
        ];
    }

    public function getUserDtoJsonType(): array
    {
        return [
            'id' => 'string',
            'name' => 'string',
            'avatar' => 'string',
            'email' => 'string',
        ];
    }

    public function getCategoryDtoJsonType(): array
    {
        return [
            'id' => 'string',
            'ownerUserId' => 'string',
            'name' => 'string',
            'position' => 'integer',
            'type' => 'string',
            'icon' => 'string',
            'isArchived' => 'integer',
            'createdAt' => 'string',
            'updatedAt' => 'string',
        ];
    }

    public function getPayeeDtoJsonType(): array
    {
        return [
            'id' => 'string',
            'ownerUserId' => 'string',
            'name' => 'string',
            'position' => 'integer|null',
            'isArchived' => 'integer',
            'createdAt' => 'string',
            'updatedAt' => 'string',
        ];
    }

    public function getTagDtoJsonType(): array
    {
        return [
            'id' => 'string',
            'ownerUserId' => 'string',
            'name' => 'string',
            'position' => 'integer|null',
            'isArchived' => 'integer',
            'createdAt' => 'string',
            'updatedAt' => 'string',
        ];
    }

    public function getAccountDtoJsonType(): array
    {
        return [
            'id' => 'string',
            'owner' => $this->getUserDtoJsonType(),
            'folderId' => 'string|null',
            'name' => 'string',
            'position' => 'integer',
            'currency' => $this->getCurrencyDtoJsonType(),
            'balance' => 'float|integer',
            'type' => 'integer',
            'icon' => 'string',
            'sharedAccess' => 'array',
        ];
    }

    public function getSharedAccessDtoJsonType(): array
    {
        return [
            'user' => $this->getUserDtoJsonType(),
            'role' => 'string',
        ];
    }

    public function getAccountFolderDtoJsonType(): array
    {
        return [
            'id' => 'string',
            'name' => 'string',
            'position' => 'integer',
        ];
    }

    public function getCurrencyDtoJsonType(): array
    {
        return [
            'id' => 'string',
            'alias' => 'string',
            'sign' => 'string',
        ];
    }

    public function getConnectionInviteDtoJsonType(): array
    {
        return [
            'code' => 'string',
            'expiredAt' => 'string',
        ];
    }
}
