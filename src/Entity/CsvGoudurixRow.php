<?php

namespace App\Entity;

/**
 * Classe pour représenter une ligne du CSV (sans être une entité Doctrine)
 */
class CsvGoudurixRow
{
    private ?string $idaction = null;
    private ?string $idSituD = null;
    private ?string $idDommage = null;
    private ?string $idMesure = null;
    private ?string $unite = null;
    private ?string $service = null;
    private ?string $site = null;
    private ?string $numero = null;
    private ?string $risque = null;
    private ?string $situationDangereuse = null;
    private ?string $dommage = null;
    private ?string $statut = null;
    private ?string $responsable = null;
    private ?string $prioriteFinale = null;
    private ?string $etat = null;
    private ?string $mesure = null;
    private ?string $mentionSpeciale = null;
    private ?string $periodicite = null;
    private ?string $prochainControle = null;
    private ?string $nPdf = null;
    private ?string $retourAction = null;

    // Getters et setters
    public function getIdaction(): ?string { return $this->idaction; }
    public function setIdaction(?string $idaction): self { $this->idaction = $idaction; return $this; }

    public function getIdSituD(): ?string { return $this->idSituD; }
    public function setIdSituD(?string $idSituD): self { $this->idSituD = $idSituD; return $this; }

    public function getIdDommage(): ?string { return $this->idDommage; }
    public function setIdDommage(?string $idDommage): self { $this->idDommage = $idDommage; return $this; }

    public function getIdMesure(): ?string { return $this->idMesure; }
    public function setIdMesure(?string $idMesure): self { $this->idMesure = $idMesure; return $this; }

    public function getUnite(): ?string { return $this->unite; }
    public function setUnite(?string $unite): self { $this->unite = $unite; return $this; }

    public function getService(): ?string { return $this->service; }
    public function setService(?string $service): self { $this->service = $service; return $this; }

    public function getSite(): ?string { return $this->site; }
    public function setSite(?string $site): self { $this->site = $site; return $this; }

    public function getNumero(): ?string { return $this->numero; }
    public function setNumero(?string $numero): self { $this->numero = $numero; return $this; }

    public function getRisque(): ?string { return $this->risque; }
    public function setRisque(?string $risque): self { $this->risque = $risque; return $this; }

    public function getSituationDangereuse(): ?string { return $this->situationDangereuse; }
    public function setSituationDangereuse(?string $situationDangereuse): self { $this->situationDangereuse = $situationDangereuse; return $this; }

    public function getDommage(): ?string { return $this->dommage; }
    public function setDommage(?string $dommage): self { $this->dommage = $dommage; return $this; }

    public function getStatut(): ?string { return $this->statut; }
    public function setStatut(?string $statut): self { $this->statut = $statut; return $this; }

    public function getResponsable(): ?string { return $this->responsable; }
    public function setResponsable(?string $responsable): self { $this->responsable = $responsable; return $this; }

    public function getPrioriteFinale(): ?string { return $this->prioriteFinale; }
    public function setPrioriteFinale(?string $prioriteFinale): self { $this->prioriteFinale = $prioriteFinale; return $this; }

    public function getEtat(): ?string { return $this->etat; }
    public function setEtat(?string $etat): self { $this->etat = $etat; return $this; }

    public function getMesure(): ?string { return $this->mesure; }
    public function setMesure(?string $mesure): self { $this->mesure = $mesure; return $this; }

    public function getMentionSpeciale(): ?string { return $this->mentionSpeciale; }
    public function setMentionSpeciale(?string $mentionSpeciale): self { $this->mentionSpeciale = $mentionSpeciale; return $this; }

    public function getPeriodicite(): ?string { return $this->periodicite; }
    public function setPeriodicite(?string $periodicite): self { $this->periodicite = $periodicite; return $this; }

    public function getProchainControle(): ?string { return $this->prochainControle; }
    public function setProchainControle(?string $prochainControle): self { $this->prochainControle = $prochainControle; return $this; }

    public function getNPdf(): ?string { return $this->nPdf; }
    public function setNPdf(?string $nPdf): self { $this->nPdf = $nPdf; return $this; }

    public function getRetourAction(): ?string { return $this->retourAction; }
    public function setRetourAction(?string $retourAction): self { $this->retourAction = $retourAction; return $this; }
}

