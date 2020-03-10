import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ModifcollaborateurComponent } from './modifcollaborateur.component';

describe('ModifcollaborateurComponent', () => {
  let component: ModifcollaborateurComponent;
  let fixture: ComponentFixture<ModifcollaborateurComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ModifcollaborateurComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ModifcollaborateurComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
