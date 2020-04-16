import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-grow',
  templateUrl: './grow.component.html',
  styleUrls: ['./grow.component.scss']
})
export class GrowComponent implements OnInit {
  public grow=[
    {titre:'Collaborateur',lien:''},
    {titre:'Teams',lien:''},
    {titre:'Session',lien:''},
    {titre:'Poste',lien:''},
    
  ]
  constructor() { }

  ngOnInit() {
  }

}
