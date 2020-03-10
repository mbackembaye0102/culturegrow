import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AjoutteamComponent } from './ajoutteam.component';

describe('AjoutteamComponent', () => {
  let component: AjoutteamComponent;
  let fixture: ComponentFixture<AjoutteamComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AjoutteamComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AjoutteamComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
