<?php
namespace App\Tests\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use App\Domain\Entity\ValueObject\Email;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\UserRepositoryInterface;
use App\Tests\ApiTester;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
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
            'category' => 'array|null',
            'description' => 'string',
            'payee' => 'array|null',
            'tag' => 'array|null',
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
        ];
    }

    public function getPayeeDtoJsonType(): array
    {
        return [
            'id' => 'string',
            'ownerUserId' => 'string',
            'name' => 'string',
            'position' => 'integer|null',
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
        ];
    }

    public function getAccountDtoJsonType(): array
    {
        return [
            'id' => 'string',
            'ownerUserId' => 'string',
            'name' => 'string',
            'position' => 'integer',
            'currencyId' => 'string',
            'currencyAlias' => 'string',
            'currencySign' => 'string',
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
}
