<?php

namespace App\Http\Controllers;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use App\Http\Requests\Amocrm\Contact\ContactIndexRequest;
use App\Http\Requests\Amocrm\Contact\ContactStoreRequest;
use App\Http\Requests\Amocrm\Contact\ContactUpdateRequest;
use App\Services\Integration\AmocrmContactService;
use Exception;
use Illuminate\Support\Collection;

class AmocrmContactController extends Controller
{
  /**
   * @param ContactStoreRequest $request
   * @param AmocrmContactService $contactService
   * @return array
   * @throws Exception
   */
  public function store(ContactStoreRequest $request, AmocrmContactService $contactService): array
  {
    $attributes = $request->validated();

    return $contactService->create(
      firstName: $attributes['first_name'],
      lastName: $attributes['last_name'],
      phones: $attributes['phones'],
      emails: $attributes['emails'],
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
  public function update(ContactUpdateRequest $request, AmocrmContactService $contactService, int $id): array
  {
    $attributes = $request->validated();

    return $contactService->update(
      id: $id,
      firstName: $attributes['first_name'] ?? null,
      phones: $attributes['phones'] ?? null,
      emails: $attributes['emails'] ?? null,
      lastName: $attributes['last_name'] ?? null,
      city: $attributes['city'] ?? null,
      country: $attributes['country'] ?? null,
      position: $attributes['position'] ?? null,
      partner: $attributes['partner'] ?? null,
    );
  }

  /**
   * @param AmocrmContactService $contactService
   * @param int $id
   * @return array
   * @throws Exception
   */
  public function show(AmocrmContactService $contactService, int $id): array
  {
    return $contactService->find($id);
  }

  /**
   * @param ContactIndexRequest $request
   * @param AmocrmContactService $contactService
   * @return Collection
   * @throws Exception
   */
  public function index(ContactIndexRequest $request, AmocrmContactService $contactService): Collection
  {
    $attributes = $request->validated();

    return $contactService->list(
      with: $attributes['with'] ?? [],
      filter: $attributes['filter'] ?? [],
      page: $attributes['page'] ?? 1,
      limit: $attributes['limit'] ?? 50,
    )->map(fn($contactModel) => $contactModel->attributes);
  }
}
