import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ModifteamComponent } from './modifteam.component';

describe('ModifteamComponent', () => {
  let component: ModifteamComponent;
  let fixture: ComponentFixture<ModifteamComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ModifteamComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ModifteamComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
