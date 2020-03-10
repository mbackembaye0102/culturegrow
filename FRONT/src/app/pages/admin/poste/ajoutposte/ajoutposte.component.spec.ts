import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AjoutposteComponent } from './ajoutposte.component';

describe('AjoutposteComponent', () => {
  let component: AjoutposteComponent;
  let fixture: ComponentFixture<AjoutposteComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AjoutposteComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AjoutposteComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
