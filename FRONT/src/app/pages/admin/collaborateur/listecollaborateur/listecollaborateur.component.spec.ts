import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ListecollaborateurComponent } from './listecollaborateur.component';

describe('ListecollaborateurComponent', () => {
  let component: ListecollaborateurComponent;
  let fixture: ComponentFixture<ListecollaborateurComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ListecollaborateurComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ListecollaborateurComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
