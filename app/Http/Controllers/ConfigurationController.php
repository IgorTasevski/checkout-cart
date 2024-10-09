<?php

namespace App\Http\Controllers;

use App\Http\Requests\Configuration\StoreConfigurationRequest;
use App\Http\Requests\Configuration\UpdateConfigurationRequest;
use App\Http\Resources\Configuration\ConfigurationResource;
use App\Repositories\Contracts\ConfigurationRepositoryInterface;
use Illuminate\Http\JsonResponse;

class ConfigurationController extends Controller
{

    public function __construct(private readonly ConfigurationRepositoryInterface $configurationRepository)
    {
    }

    public function index(): JsonResponse
    {
        $configurations = $this->configurationRepository->getAll();
        return response()->json(ConfigurationResource::collection($configurations));
    }

    public function show($id): JsonResponse
    {
        $configuration = $this->configurationRepository->findById($id);

        if ($configuration) {
            return response()->json(new ConfigurationResource($configuration));
        }

        return response()->json(['error' => 'Configuration not found'], 404);
    }

    public function store(StoreConfigurationRequest $request): JsonResponse
    {
        $configuration = $this->configurationRepository->create($request->validated());
        return response()->json(new ConfigurationResource($configuration), 201);
    }

    public function update(UpdateConfigurationRequest $request, $id): JsonResponse
    {
        $updated = $this->configurationRepository->update($id, $request->validated());

        if ($updated) {
            return response()->json(['message' => 'Configuration updated successfully']);
        }

        return response()->json(['error' => 'Configuration not found'], 404);
    }

    public function destroy($id): JsonResponse
    {
        $deleted = $this->configurationRepository->delete($id);

        if ($deleted) {
            return response()->json(['message' => 'Configuration deleted successfully']);
        }

        return response()->json(['error' => 'Configuration not found'], 404);
    }
}
