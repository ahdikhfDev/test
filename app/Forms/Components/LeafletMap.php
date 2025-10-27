<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;

class LeafletMap extends Field
{
    protected string $view = 'forms.components.leaflet-map';

    protected float $defaultLatitude = -6.2088;
    protected float $defaultLongitude = 106.8456;
    protected int $defaultZoom = 13;
    protected bool $draggable = true;
    protected bool $searchable = true;

    public function defaultLocation(float $latitude, float $longitude): static
    {
        $this->defaultLatitude = $latitude;
        $this->defaultLongitude = $longitude;

        return $this;
    }

    public function defaultZoom(int $zoom): static
    {
        $this->defaultZoom = $zoom;

        return $this;
    }

    public function draggable(bool $draggable = true): static
    {
        $this->draggable = $draggable;

        return $this;
    }

    public function searchable(bool $searchable = true): static
    {
        $this->searchable = $searchable;

        return $this;
    }

    public function getDefaultLatitude(): float
    {
        return $this->defaultLatitude;
    }

    public function getDefaultLongitude(): float
    {
        return $this->defaultLongitude;
    }

    public function getDefaultZoom(): int
    {
        return $this->defaultZoom;
    }

    public function isDraggable(): bool
    {
        return $this->draggable;
    }

    public function isSearchable(): bool
    {
        return $this->searchable;
    }
}