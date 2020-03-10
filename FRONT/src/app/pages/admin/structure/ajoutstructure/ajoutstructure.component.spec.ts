import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AjoutstructureComponent } from './ajoutstructure.component';

describe('AjoutstructureComponent', () => {
  let component: AjoutstructureComponent;
  let fixture: ComponentFixture<AjoutstructureComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AjoutstructureComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AjoutstructureComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
