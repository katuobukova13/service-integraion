<?php

namespace App\Http\Controllers;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\InvalidArgumentException;
use App\Http\Requests\ContactRequest;
use App\Services\Integration\AmocrmContactService;
use Exception;
use JetBrains\PhpStorm\ArrayShape;

class AmocrmContactController extends Controller
{
  /**
   * @throws InvalidArgumentException
   * @throws AmoCRMApiException
   * @throws AmoCRMMissedTokenException
   * @throws AmoCRMoAuthApiException
   */
  #[ArrayShape(['contact' => "\App\Modules\Integration\Domain\Amocrm\Contact\ContactModel"])]
  public function store(ContactRequest $request, AmocrmContactService $contactService): array
  {
    $attributes = $request->all();

    return $contactService->create(
      firstName: $attributes['first_name'],
      lastName: $attributes['last_name'],
      phone: $attributes['phone'],
      email: $attributes['email'],
      city: $attributes['city'] ?? null,
      country: $attributes['country'] ?? null,
      position: $attributes['position'] ?? null,
      partner: $attributes['partner'] ?? null,
    );
  }

  /**
   * @throws AmoCRMApiException
   * @throws AmoCRMoAuthApiException
   * @throws AmoCRMMissedTokenException
   */
  #[ArrayShape(['contact' => "\App\Modules\Integration\Domain\Amocrm\Contact\ContactModel"])]
  public function update(ContactRequest $request, AmocrmContactService $contactService, int $id): array
  {
    $attributes = $request->all();

    return $contactService->update(
      id: $id,
      firstName: $attributes['first_name'] ?? null,
      phone: $attributes['phone'] ?? null,
      email: $attributes['email'] ?? null,
      lastName: $attributes['last_name'] ?? null,
      city: $attributes['city'] ?? null,
      country: $attributes['country'] ?? null,
      position: $attributes['position'] ?? null,
      partner: $attributes['partner'] ?? null,
    );
  }

  /**
   * @throws AmoCRMoAuthApiException
   * @throws AmoCRMApiException
   * @throws AmoCRMMissedTokenException
   */

  #[ArrayShape(['contact' => "\App\Modules\Integration\Domain\Amocrm\Contact\ContactModel"])]
  public function show(AmocrmContactService $contactService, int $id): array
  {
    return $contactService->find($id);
  }

  /**
   * @param ContactRequest $request
   * @param AmocrmContactService $contactService
   * @return array
   * @throws Exception
   */
  #[ArrayShape(['contacts' => "\Illuminate\Support\Collection"])]
  public function index(ContactRequest $request, AmocrmContactService $contactService): array
  {
    $attributes = $request->request->all();

    return $contactService->list(
      with: $attributes['with'] ?? [],
      filter: $attributes['filter'] ?? [],
      page: $attributes['page'] ?? null,
      limit: $attributes['limit'] ?? null);
  }
}
