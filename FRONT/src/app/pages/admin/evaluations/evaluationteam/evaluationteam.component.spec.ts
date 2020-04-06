import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EvaluationteamComponent } from './evaluationteam.component';

describe('EvaluationteamComponent', () => {
  let component: EvaluationteamComponent;
  let fixture: ComponentFixture<EvaluationteamComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EvaluationteamComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EvaluationteamComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
